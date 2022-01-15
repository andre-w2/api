<?php 

session_start();

include_once './core/system/lib/mysqli.class.php';
include_once './core/system/lib/navigation.php';

function getConnection(){
	$f_json = "./core/config.json";
    $json = file_get_contents($f_json);
	$jsonRead = json_decode($json, TRUE);
    
    $conn = new SafeMySQL(
    	['user' => $jsonRead['db']['user'], 
    	'pass' => $jsonRead['db']['password'],
    	'db' => $jsonRead['db']['db_name'], 
    	'charset' => 'utf8']);

    return $conn;
 }

?>