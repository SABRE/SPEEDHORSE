<?php

Header("content-type: application/x-javascript");

$keys = $_GET['keys'];

$my_js = file_get_contents("http://cdn.thinkcreative.com/players/$keys.js");

//$my_js = preg_replace("/^.*document\.write/s", "document.write", $my_js);

$my_js = preg_replace("/document\.write.*;/", "", $my_js);

echo $my_js;

?>