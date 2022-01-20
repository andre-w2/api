<?php
    require_once __DIR__ .'./core/system/db.php';
    require_once __DIR__ .'/core/cors.php';
	
	$db = getConnection();
    
    $url = $_SERVER['REQUEST_URI'];
	$url = explode('?', $url);
	$url = $url[0];
 
	if(strpos($url, 'register')) {
		require_once __DIR__ . '/class/register/register.php';
	}
?>