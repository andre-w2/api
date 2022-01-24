<?php
require_once __DIR__ .'./core/system/db.php';
require_once __DIR__ .'/core/cors.php';
require_once __DIR__ .'/core/system/Router.php';

Router::route('/register', function(){
	$db = getConnection();
	require_once __DIR__ . '/class/register/register.php';
});

Router::route('/auth', function(){
	$db = getConnection();
	require_once __DIR__ . '/class/auth/auth.php';
});

Router::execute($_SERVER['REQUEST_URI']);
?>