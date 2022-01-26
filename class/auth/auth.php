<?php
require_once './core/system/system.config.php';

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
    $pass = (!empty($input['password'])) ? $input['password'] : false;
    $err = false;

    if ($email && $pass) {
       if (strlen($email) > 50) {
         $returnData = msg(0, 'Email длинный, должно быть 50 символов');
         $err = true;
     }

     if (strlen($pass) > 150) {
       $returnData = msg(0, 'Пароль длинный, должно быть 150 символов');
       $err = true;
   }

   $queryUser = $db->query("SELECT * FROM users WHERE email = ?s", $email);
   $numUser = $db->numRows($queryUser);

   if ($numUser) {
       $fetchUser = $db->fetch($queryUser);
       $password = md5(sha1(md5($pass)));

       if ($fetchUser['password'] == $password) {
           $returnData = msg(1, $fetchUser);
       } else {
           $returnData = msg(0, 'Данные не найдены');
       }

   } else {
    $returnData = msg(0, 'Данные не найдены');
}


} else {
 $returnData = msg(0, 'Заполните данные');
}
}

header("Content-Type: application/json; charset=UTF-8");
echo json_encode($returnData);
