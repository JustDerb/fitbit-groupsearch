<?php
$whitelist = array('localhost', '127.0.0.1');

if(in_array($_SERVER['HTTP_HOST'], $whitelist)){
    define("LOCALHOST", true);
}

function getAdBlock($width, $height) {
	return '<div style="width:'.$width.'px;height:'.$height.'px;line-height:'.$height.'px;text-align:center;background-color:#CCCCCC;display:inline-block">ADSENSE BLOCK</div>';
}
	
?>