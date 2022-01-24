<?php

require './core/jwt/jwt.php';
require './core/system/system.config.php';

$jwt_create = new JwtHandler;
$jwt = $jwt_create->jwtEncodeData('instagram.com');


function msg($success, $message, $extra = []){
    return array_merge([
        'success' => $success,
        'message' => $message
    ], $extra);
}

$data = file_get_contents('php://input');
$input = json_decode($data, TRUE);

$returnData = [];
if ($_SERVER["REQUEST_METHOD"] != "POST") {

    $returnData = msg(0, 'Page Not Found!');

} else if(!is_null($input)) {

    foreach($input as $key=>$value){
        $input[$key] = XSS($value);
    }

    $email = (!empty($input['email'])) ? $input['email'] : false;
    $name = (!empty($input['name'])) ? $input['name'] : false;
    $nickname = (!empty($input['username'])) ? $input['username'] : false;
    $pass = (!empty($input['password'])) ? $input['password'] : false;
    $err = false;

    if ($email && $name && $nickname && $pass) {
     if (strlen($email) > 50) {
       $returnData = msg(0, 'Email длинный, должно быть 50 символов');
       $err = true;
   }

   if (strlen($name) > 50) {
       $returnData = msg(0, 'Имя длинное, должно быть 50 символов');
       $err = true;
   }

   if (strlen($nickname) > 50) {
      $returnData = msg(0, 'Никнейм длинный, должно быть 50 символов');
      $err = true;
  }

  if (strlen($pass) > 150) {
     $returnData = msg(0, 'Пароль длинный, должно быть 150 символов');
     $err = true;
 }

 $queryEmail = $db->query('SELECT * from users WHERE email = ?s', $email);
 $numEmail = $db->numRows($queryEmail);

 if ($numEmail) {
    $returnData = msg(0, 'Пользователь  с таким email есть!');
    $err = true;
}

$queryNick = $db->query('SELECT * from users WHERE nickname = ?s', $nickname);
$numNick = $db->numRows($queryNick);

if ($numNick) {
    $returnData = msg(0, 'Пользователь  с таким никнейм есть!');
    $err = true;
}

if (!$err) {
    $password = md5(sha1(md5($pass)));

    $query = $db->query('INSERT INTO users (name, email, password, nickname, token) VALUES (?s, ?s, ?s, ?s, ?s)', $name, $email, $password, $nickname, $jwt);

    if ($query) {
        $queryUser = $db->query('SELECT * from users WHERE token = ?s', $jwt);
        $returnData = msg(1, $db->fetch($queryUser));
    } else {
        $returnData = msg(0, 'Произшошла ошибка');
    }
}

} else {
   $returnData = msg(0, 'Заполните данные');
}
}

header("Content-Type: application/json; charset=UTF-8");
echo json_encode($returnData);
