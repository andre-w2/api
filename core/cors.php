<?php

$f_json = "./core/config.json";
$json = file_get_contents($f_json);
$jsonRead = json_decode($json, TRUE);

header("Access-Control-Allow-Origin: ". $jsonRead['cors']);
header("Access-Control-Allow-Methods: ". $methods);
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Accept, X-PINGOTHER, Content-Type");


?>