<?php
    require_once __DIR__ .'/cors.php';
    
    $url = $_SERVER['REQUEST_URI'];
	$url = explode('?', $url);
	$url = $url[0];
 
	if(strpos($url, 'register')) {
		require_once __DIR__ . '/class/register/register.php';
	}
?>