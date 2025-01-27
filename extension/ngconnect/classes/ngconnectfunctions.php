<?php

class ngConnectFunctions
{
	public static function createUser($authResult)
	{
		$ngConnectINI = eZINI::instance('ngconnect.ini');
		$siteINI = eZINI::instance('site.ini');

		$defaultUserPlacement = $ngConnectINI->variable('LoginMethod_' . $authResult['login_method'], 'DefaultUserPlacement');
		$placementNode = eZContentObjectTreeNode::fetch($defaultUserPlacement);

		if(!($placementNode instanceof eZContentObjectTreeNode))
		{
			$defaultUserPlacement = $siteINI->variable('UserSettings', 'DefaultUserPlacement');
			$placementNode = eZContentObjectTreeNode::fetch($defaultUserPlacement);

			if(!($placementNode instanceof eZContentObjectTreeNode))
			{
				return false;
			}
		}

		$contentClass = eZContentClass::fetch($siteINI->variable( 'UserSettings', 'UserClassID'));
		$userCreatorID = $siteINI->variable('UserSettings', 'UserCreatorID');
		$defaultSectionID = $siteINI->variable('UserSettings', 'DefaultSectionID');

		$db = eZDB::instance();
		$db->begin();

		$contentObject = $contentClass->instantiate($userCreatorID, $defaultSectionID);
		$contentObject->store();

		$nodeAssignment = eZNodeAssignment::create(array('contentobject_id' => $contentObject->attribute('id'),
													'contentobject_version' => 1,
													'parent_node' => $placementNode->attribute('node_id'),
													'is_main' => 1));
		$nodeAssignment->store();

		$currentTimeStamp = eZDateTime::currentTimeStamp();
		$version = $contentObject->currentVersion();
		$version->setAttribute('modified', $currentTimeStamp);
		$version->setAttribute('status', eZContentObjectVersion::STATUS_DRAFT);
		$version->store();

		$dataMap = $version->dataMap();
		self::fillUserObject($version->dataMap(), $authResult);
		//print_r($authResult);
		//exit;
		if(!isset($dataMap['user_account']))
		{
			$db->rollback();
			return false;
		}

		//$userLogin = 'speedhorse_' . $authResult['login_method'] . '_' . $authResult['id'];
		
		$userLogin = $authResult['email'];
		//print_r($authResult);
		//exit;
		$userPassword = (string) rand();

		if(eZUser::requireUniqueEmail())
			$userExists = eZUser::fetchByEmail($authResult['email']) instanceof eZUser;
		else
			$userExists = false;

		if(strlen($authResult['email']) == 0 || $userExists)
			$email = md5('speedhorse_' . $authResult['login_method'] . '_' . $authResult['id']) . '@localhost.local';
		else
			$email = $authResult['email'];

		$user = new eZUser(
			array(
				'contentobject_id'		=> $contentObject->attribute('id'),
				'email'					=> $email,
				'login'					=> $userLogin,
				'password_hash'			=> md5($userPassword),
				'password_hash_type'	=> 1
			)
		);
		$user->store();
		
		
		include_once( 'lib/ezutils/classes/ezmail.php' );
        include_once( 'lib/ezutils/classes/ezmailtransport.php' );
		// Send Email to User
        include_once( 'kernel/common/template.php' );
        $tpl = templateInit();
		
		
		
		
        // Fetch site hostname
        //$tpl->setVariable( 'site_host', $siteUrl );

        $to = $email;
        $subject = "New user registration";
		$tpl->setVariable( 'email', $email );
        $tpl->setVariable( 'pass', $userPassword );
		$tpl->setVariable( 'fname', $authResult['first_name'] );
		

        $body = $tpl->fetch("design:user/sendemail.tpl");
		
       // $results = $this->sendNotificationEmail( $to, $subject, $body );

		
		
        $mail = new eZMail();
        $mail->setReceiver( $to );
        $mail->setSubject( $subject );
        $mail->setBody( $body );

        // print_r( $mail ); die();
        eZMailTransport::send( $mail );
		

		$userSetting = new eZUserSetting(
			array(
				'is_enabled'	=> true,
				'max_login'		=> 0,
				'user_id'		=> $contentObject->attribute('id')
			)
		);
		$userSetting->store();

		$dataMap['user_account']->setContent($user);
		$dataMap['user_account']->store();

		$operationResult = eZOperationHandler::execute('content', 'publish', array('object_id' => $contentObject->attribute('id'), 'version' => $version->attribute('version')));

		if((array_key_exists('status', $operationResult) && $operationResult['status'] == eZModuleOperationInfo::STATUS_CONTINUE))
		{
			$db->commit();
			return $user;
		}

		$db->rollback();
		return false;
	}

	public static function updateUser($user, $authResult)
	{
		$currentTimeStamp = eZDateTime::currentTimeStamp();

		$contentObject = $user->contentObject();
		if(!($contentObject instanceof eZContentObject))
		{
			return false;
		}

		$version = $contentObject->currentVersion();

		$db = eZDB::instance();
		$db->begin();

		$version->setAttribute('modified', $currentTimeStamp);
		$version->store();

		self::fillUserObject($version->dataMap(), $authResult);

		if($authResult['email'] != $user->Email)
		{
			if(eZUser::requireUniqueEmail())
				$userExists = eZUser::fetchByEmail($authResult['email']) instanceof eZUser;
			else
				$userExists = false;

			if(strlen($authResult['email']) == 0 || $userExists)
				$email = md5('speedhorse_' . $authResult['login_method'] . '_' . $authResult['id']) . '@localhost.local';
			else
				$email = $authResult['email'];

			$user->setAttribute('email', $email);
			$user->store();
		}

		$contentObject->setName($contentObject->contentClass()->contentObjectName($contentObject));
		$contentObject->store();

		$db->commit();
		return $user;
	}

	private static function fillUserObject($dataMap, $authResult)
	{
		//print_r($authResult);
		//exit;
		
		if(isset($dataMap['first_name']))
		{
			$dataMap['first_name']->fromString($authResult['first_name']);
			$dataMap['first_name']->store();
		}

		if(isset($dataMap['last_name']))
		{
			$dataMap['last_name']->fromString($authResult['last_name']);
			$dataMap['last_name']->store();
		}
		
		if(isset($dataMap['city']))
		{
			$dataMap['city']->fromString($authResult['city']);
			$dataMap['city']->store();
		}
		
		if(isset($dataMap['scity']))
		{
			$dataMap['scity']->fromString($authResult['city']);
			$dataMap['scity']->store();
		}

		if(isset($dataMap['image']) && strlen($authResult['picture']) > 0)
		{
			$storageDir = eZSys::storageDirectory() . '/ngconnect';
			if(!(file_exists($storageDir))) mkdir($storageDir);
			$fileName = $storageDir . '/' . $authResult['login_method'] . '_' . $authResult['id'];

			$image = ngConnectFunctions::fetchDataFromUrl($authResult['picture'], true, $fileName);
			if($image)
			{
				$dataMap['image']->fromString($fileName);
				$dataMap['image']->store();
				unlink($fileName);
			}
		}
	}

	public static function fetchDataFromUrl($url, $saveToFile = false, $fileName = '')
	{
		$handle = curl_init($url);
		if(!$handle) return false;

		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($handle, CURLOPT_TIMEOUT, 10);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_HEADER, false);
		curl_setopt($handle, CURLOPT_POST, 0);
		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($handle, CURLOPT_MAXREDIRS, 1);

		if($saveToFile)
		{
			$fileHandle = fopen($fileName, 'w');
			if(!$fileHandle)
			{
				curl_close($handle);
				return false;
			}

			curl_setopt($handle, CURLOPT_FILE, $fileHandle);
		}

		$data = curl_exec($handle);
		curl_close($handle);

		if($saveToFile)
		{
			fclose($fileHandle);
		}

		return $data;
	}

	public static function connectUser($userID, $loginMethod, $networkUserID)
	{
		$ngConnect = ngConnect::fetch($userID, $loginMethod, $networkUserID);
		if(!($ngConnect instanceof ngConnect))
		{
			$ngConnect = new ngConnect(array(
				'user_id'				=> $userID,
				'login_method'			=> $loginMethod,
				'network_user_id'		=> $networkUserID
			));
			$ngConnect->store();
		}
	}
}

?>