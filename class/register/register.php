<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require './class/db/db.php';
require './class/jwt/jwt.php';
require './system/system.config.php';

$db_connection = new Database;
$db = $db_connection->getConnection();

$jwt_create = new JwtHandler;
$jwt = $jwt_create->jwtEncodeData('instagram.com');


function msg($success, $status, $message, $extra = [])
{
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message,
    ], $extra);
}

$data = file_get_contents('php://input');
$input = json_decode($data, TRUE);

$returnData = [];

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    $returnData = msg(0, 404, 'Page Not Found!');

} else if(!is_null($input)) {

    foreach($input as $key=>$value){
        $input[$key] = XSS($value);
    }

    $email = (!empty($input['email'])) ? $input['email'] : false;
    $name = (!empty($input['name'])) ? $input['name'] : false;
    $nickname = (!empty($input['nickname'])) ? $input['nickname'] : false;
    $pass = (!empty($input['pass'])) ? $input['pass'] : false;
    $err = false;

    if ($email && $name && $nickname && $pass) {
       if (strlen($email) > 50) {
         $returnData = msg(0, 200, 'Email длинный, должно быть 50 символов');
         $err = true;
       }

       if (strlen($name) > 50) {
         $returnData = msg(0, 200, 'Имя длинное, должно быть 50 символов');
         $err = true;
       }

       if (strlen($nickname) > 50) {
          $returnData = msg(0, 200, 'Никнейм длинный, должно быть 50 символов');
         $err = true;
       }

       if (strlen($pass) > 150) {
           $returnData = msg(0, 200, 'Пароль длинный, должно быть 150 символов');
         $err = true;
       }

        $query = $db->query('SELECT name from users WHERE name = ?s', $name);
        $queryRows = $db->numRows($query);

        if ($queryRows) {
            $returnData = msg(0, 200, 'Пользователь  с таким email есть!');
            $err = true;
        }

        $query = $db->query('SELECT 
                                nickname 
                             FROM 
                                users 
                            WHERE nickname = ?s', $nickname);
        $queryRows = $db->numRows($query);

        if ($queryRows) {
            $returnData = msg(0, 200, 'Пользователь  с таким никнейм есть!');
            $err = true;
        }

        if (!$err) {
            $password = md5(sha1(md5($pass)));

            $query = $db->query('INSERT INTO users (name, email, password, nickname, token) VALUES (?s, ?s, ?s, ?s, ?s)', $name, $email, $password, $nickname, $jwt);

            if ($query) {
                $returnData = msg(1, 200, "Регистрация успешна || $jwt");
            } else {
                $returnData = msg(0, 503, 'Произшошла ошибка');
            }
        }

    } else {
         $returnData = msg(0, 403, 'Заполните данные');
    }
}




echo json_encode($returnData);
