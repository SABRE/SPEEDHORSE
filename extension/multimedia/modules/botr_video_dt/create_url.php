<?
eZDebug::writeDebug('test');
$ini = new eZINI('botr.ini');
$Key = $ini->variable('BOTRSettings', 'Key');
$Private = $ini->variable('BOTRSettings', 'Private');
$botr_api = new Botr_API($Key,$Private);
$vars = $_POST;

if (!count($vars)) $vars = $_GET;

foreach ($vars as $key => $val) {
	$evalme = "\$$key='$val';";
	eval($evalme);
}

$response = $botr_api->call('/videos/create', array('title'=>$title,'description'=>$description,'author'=>$author,'tags'=>$tags));

if ($response['status'] != 'error') { 
	
$url  = 'http://'.$response['link']['address'].$response['link']['path'];
$url .= '?key='.$response['link']['query']['key'];
$url .= '&api_format=xml';
$url .= '&redirect_address=http://'.$_SERVER["SERVER_NAME"].$redirect_uri;
$url .=  '&token='.$response['link']['query']['token'];
echo $url;

} else {

print_r($response);	

}
			
eZExecution::cleanExit();		

?>