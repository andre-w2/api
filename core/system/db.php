<?php 

session_start();

include_once './core/system/lib/mysqli.class.php';
include_once './core/system/lib/navigation.php';

function getConnection(){
	try {
		$f_json = "./core/config.json";
	    $json = file_get_contents($f_json);
		$jsonRead = json_decode($json, TRUE);
	    
	    $conn = new SafeMySQL(
	    	[
	    		'user' => $jsonRead['db']['user'], 
	    		'pass' => $jsonRead['db']['password'],
	    		'db' => $jsonRead['db']['db_name'], 
	    		'charset' => 'utf8'
	    	]
	    );

	    return $conn;
	} catch(Exception $e) {
		echo "<div style='position:relative;
						  padding:1rem 1rem;
						  margin-bottom: 1rem;
						  border:1px solid transparent;
						  border-radius: .25rem;
						  color: #842029;
						  background-color: #f8d7da;
						  border-color: #f5c2c7'>
			{$e->getMessage()}
		</div>";
		exit();
	}
 }

?>