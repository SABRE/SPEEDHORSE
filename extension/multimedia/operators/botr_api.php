<?php

	class botr_apiOperator {
		
	var $Operators;

	function botr_apiOperator(){
		$this->Operators = array('botr_api', 'botr_player');
	}

	function &operatorList(){
		return $this->Operators;
	}

	function namedParameterPerOperator(){
        	return true;
	}

	function namedParameterList(){
	        return array(
			'botr_api' => array('vid_id' => array( 'type' => 'string', 'required' => false, 'default' => null ) ),
			'botr_player' => array('player_id' => array( 'type' => 'string', 'required' => false, 'default' => null ) ),
		);
	}
	

	function modify(&$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters) {
		
		$ini = new eZINI('botr.ini');
		$Key = $ini->variable('BOTRSettings', 'Key');
		$Private = $ini->variable('BOTRSettings', 'Private');
		$botr_api = new Botr_API($Key,$Private);
		
		
		if ($operatorName == 'botr_api') {

			$response1 = array('video' => array('status'=> null));
			$cc = 0;

			while ($response1['video']['status'] != 'ready' && $cc < 5) {

			if ($cc > 0) sleep(5);
			$response1 = $botr_api->call('/videos/show', array('video_key'=>$namedParameters['vid_id']));
			$response2 = $botr_api->call('/videos/conversions/list', array('video_key'=>$namedParameters['vid_id']));
			$cc = $cc + 1;

			}

			$pa = $si = $du = $di = array();

			foreach ($response2['conversions'] as $c) {
				$pa[]=$c['link']['protocol'].$c['link']['address'].$c['link']['path'];
				$si[]=$c['filesize'];
				$du[]=$c['duration'];
				$di[]=$c['width'] . "x" . $c['height'];
				$tmp = $c['key'];
				$se[]="<input type='radio' name='vidselect' value='$tmp'/>";
			}

			$pr = "<img src='http://cdn.thinkcreative.com/thumbs/" . $namedParameters['vid_id'] . "-100.jpg'/>";
			$pa = implode($pa, '<br/>');
			$si = implode($si, '<br/>');
			$du = implode($du, '<br/>');
			$di = implode($di, '<br/>');
			$se = implode($se, '<br/>');

			$html = "<table class='list' cellspacing='0'><tr><th class='tight'>Preview</th><th>Path</th><th>Size</th><th>Duration</th><th>Dimensions</th></tr><tr><td>$pr</td><td>$pa</td><td>$si</td><td>$du</td><td>$di</td></tr></table>";
	
			$operatorValue = array('html' => $html, 'response' => array($response1, $response2), 'args' => $botr_api->getargs());
		
		} else {
			
			$out = $botr_api->call('/players/show', array('player_key'=>$namedParameters['player_id']));
			
			$operatorValue = $out['player'];
		
		}
		
	}
}

?>
