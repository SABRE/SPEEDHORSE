<?

$vars = (count($_POST)) ? $_POST : $_GET;

if (array_key_exists('video_key', $vars)) {

$ini = new eZINI('botr.ini');
$Key = $ini->variable('BOTRSettings', 'Key');
$Private = $ini->variable('BOTRSettings', 'Private');
$botr_api = new Botr_API($Key,$Private);

$response1 = array('video' => array('status'=> null));
$cc = 0;

$response1 = $botr_api->call('/videos/show', array('video_key'=>$vars['video_key']));
$response2 = $botr_api->call('/videos/conversions/list', array('video_key'=>$vars['video_key']));

if ($response1['video']['status'] != 'ready') {
	
	echo "<xmp>" .  json_encode(array('conversion' => '', 'html'=>"<p>You're video has been uploaded. Conversion details will appear shortly.</p>", 'video_key' => $vars['video_key'], 'response' => array($response1, $response2))) . "</xmp>";
	eZExecution::cleanExit();	
	
}



$pa = $si = $du = $di = $se = array();

foreach ($response2['conversions'] as $count => $c) {
	$pa[]=$c['link']['protocol'].$c['link']['address'].$c['link']['path'];
	$si[]=$c['filesize'];
	$du[]=$c['duration'];
	$di[]=$c['width'] . "x" . $c['height'];
	$tmp = $c['key'];
	$checked = $count ? '' : 'checked';
	$se[]="<input type='radio' name='vidselect' value='$tmp' $checked/>";
}

$pr = "<img src='http://cdn.thinkcreative.com/thumbs/" . $vars['video_key'] . "-100.jpg'/>";
$pa = implode($pa, '<br/>');
$si = implode($si, '<br/>');
$du = implode($du, '<br/>');
$di = implode($di, '<br/>');
$se = implode($se, '<br/>');

$html = "<table class='list' cellspacing='0'><tr><th class='tight'>Preview</th><th>Path</th><th>Size</th><th>Duration</th></tr><tr><td>$pr</td><td>$pa</td><td>$si</td><td>$du</td></tr></table>";

echo "<xmp>" .  json_encode(array('conversion' => $response2['conversions'][0]['key'], 'html'=>$html, 'video_key' => $vars['video_key'], 'response' => array($response1, $response2))) . "</xmp>";

}
			
eZExecution::cleanExit();		

?>