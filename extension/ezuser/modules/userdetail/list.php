<?php
// modul1/list.php - Funktionsdatei der View list

// notwendige php Bibliothek fuer Templatefunktionen bekannt machen
include_once( "kernel/common/template.php" );
include_once('lib/ezutils/classes/ezhttptool.php');
include_once( 'lib/ezdb/classes/ezdb.php' );
$http = eZHTTPTool::instance();
$db = eZDB::instance();
$tpl = templateInit();
$viewarr= array();
// $db->query("select * from ezbook order by id");	
//$sql="select * from ezbook order by id";
//$result= $db->query("select * from ezbook order by id");
//if($http->hasVariable('ParamOne'))
$userID =  $Params['ParamOne'];
//print $userid;
$currentUser = eZUser::fetch($userID);


$contentObject = $currentUser->attribute( 'contentobject' );
$dataMap = $contentObject->attribute( 'data_map' );
{
$email=$currentUser->attribute( 'email' ) ;
foreach( $dataMap as $key => $value ) //looping through each field
{
    $type = $value->dataType(); //looking at what type the current field is
    switch( $type->DataTypeString ) //base the switch on the type name
    {
        case 'ezimage':
            $content = $value->attribute( 'content' ); 
            $displayText = $content->displayText();
            $imageAlias = $content->imageAlias('original');
            $imagePath = $imageAlias['url'];
			//$cli->output( "$key: $displayText ($imagePath)" );
			//print "$key: $displayText ($imagePath)";
            break;
        case 'ezstring': //for basic text & ints
        case 'eztext':
        case 'ezint':
        case 'ezfloat':
           // $cli->output( "$key: ".$value->toString() );
			//print "$key: ".$value->toString();
			if($key=='first_name'){
				$first_name=$value->toString();
			}
			elseif($key=='middle_name'){
				$middle_name=$value->toString();
			}
			elseif($key=='last_name'){
				$last_name=$value->toString();
			}
			elseif($key=='office_phone_number'){
				$office_phone_number=$value->toString();
			}
			elseif($key=='cell_phone_number'){
				$cell_phone_number=$value->toString();
			}
			elseif($key=='home_phone_number'){
				$home_phone_number=$value->toString();
			}
			elseif($key=='address'){
				$address=$value->toString();
			}
			elseif($key=='saddress'){
				$saddress=$value->toString();
			}
			elseif($key=='city'){
				$city=$value->toString();
			}
			elseif($key=='scity'){
				$scity=$value->toString();
			}
			elseif($key=='state'){
				$state=$value->toString();
			}
			elseif($key=='sstate'){
				$sstate=$value->toString();
			}
			elseif($key=='zip'){
				$zip=$value->toString();
			}
			elseif($key=='szip'){
				$szip=$value->toString();
			}
			elseif($key=='country'){
				$country=$value->toString();
			}
			elseif($key=='scountry'){
				$scountry=$value->toString();
			}
            break;
        default: //by default let's show what the type is (along with the toString representation):
            //$cli->output( $key.' ' . $type->DataTypeString . ' - ' . $value->toString() );
			//print $key.' ' . $type->DataTypeString . ' - ' . $value->toString();
			if($key=='first_name'){
				$first_name=$value->toString();
			}
			elseif($key=='middle_name'){
				$middle_name=$value->toString();
			}
			elseif($key=='last_name'){
				$last_name=$value->toString();
			}
			elseif($key=='office_phone_number'){
				$office_phone_number=$value->toString();
			}
			elseif($key=='cell_phone_number'){
				$cell_phone_number=$value->toString();
			}
			elseif($key=='home_phone_number'){
				$home_phone_number=$value->toString();
			}
			elseif($key=='address'){
				$address=$value->toString();
			}
			elseif($key=='saddress'){
				$saddress=$value->toString();
			}
			elseif($key=='city'){
				$city=$value->toString();
			}
			elseif($key=='scity'){
				$scity=$value->toString();
			}
			elseif($key=='state'){
				$state=$value->toString();
			}
			elseif($key=='sstate'){
				$sstate=$value->toString();
			}
			elseif($key=='zip'){
				$zip=$value->toString();
			}
			elseif($key=='szip'){
				$szip=$value->toString();
			}
			elseif($key=='country'){
				$country=$value->toString();
			}
			elseif($key=='scountry'){
				$scountry=$value->toString();
			}
			
			
        break;
    }
}
//query for comment and artical name


	$i=0;
	$data=$db->arrayQuery("SELECT ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND ezcontentobject.owner_id =".$userID." AND ezcontentobject.contentclass_id in ('16','17','18') and ezcontentobject.status='1'");
		foreach($data as $row)
		{
			
			$viewarr[$i]['name']=$row['name'];
			$viewarr[$i]['path']=str_replace('_','-',$row['path']);
			$i++;
		}

//query for blog post only

$k=0;
	$data=$db->arrayQuery("SELECT ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND ezcontentobject.owner_id =".$userID." AND ezcontentobject.contentclass_id ='20' and ezcontentobject.status='1'");
		foreach($data as $row)
		{
			
			$viewarr2[$k]['name']=$row['name'];
			$viewarr2[$k]['path']=str_replace('_','-',$row['path']);
			$k++;
		}
				
// query for comment only
	$j=0;
	$data1=$db->arrayQuery("SELECT ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND ezcontentobject.owner_id =".$userID." AND ezcontentobject.contentclass_id ='13' and ezcontentobject.status='1'");
		foreach($data1 as $row)
		{
			
			$viewarr1[$j]['name']=$row['name'];
			$viewarr1[$j]['path']=str_replace('_','-',$row['path']);
			$j++;
		}
	
	if($imagePath!="")
		{
		$tpl->setVariable( 'imagePath', $imagePath );
		}
	else
		{
		$imagePath="var/ezwebin_site/storage/images/noimages.jpg";
		$tpl->setVariable( 'imagePath', $imagePath );
		}
	$tpl->setVariable( 'email', $email );
	$tpl->setVariable( 'first_name', $first_name );
	$tpl->setVariable( 'middle_name', $middle_name );
	$tpl->setVariable( 'last_name', $last_name );
	$tpl->setVariable( 'office_phone_number', $office_phone_number );
	$tpl->setVariable( 'cell_phone_number', $cell_phone_number );
	$tpl->setVariable( 'home_phone_number', $home_phone_number );
	$tpl->setVariable( 'address', $address );
	$tpl->setVariable( 'saddress', $saddress );
	$tpl->setVariable( 'city', $city );
	$tpl->setVariable( 'scity', $scity );
	$tpl->setVariable( 'state', $state );
	$tpl->setVariable( 'sstate', $sstate );
	$tpl->setVariable( 'zip', $zip );
	$tpl->setVariable( 'szip', $szip );
	$tpl->setVariable( 'country', $country );
	$tpl->setVariable( 'scountry', $scountry );
	$tpl->setVariable( 'data_array', $viewarr );
	$tpl->setVariable( 'data_array1', $viewarr1 );
	$tpl->setVariable( 'data_array2', $viewarr2 );	
//print_r($viewarr);	
}
//$tpl->setVariable( 'data_array', $viewarr );
$Result = array();
$Result['content'] = $tpl->fetch( 'design:userdetail/list.tpl' );

?>