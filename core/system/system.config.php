<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'lib/mail.php';

if (isset($_SESSION['userId'])) {
  define('ID_USER', $_SESSION['userId']);
}

function XSS($var) {
  $var = trim($var);
  $var = htmlspecialchars($var);
  $var = stripslashes($var);

  return $var;
}

function clear($var){
  $var = trim($var);
  $var = abs($var);
  $var = intval($var);

  return $var;
}

function CheckEmail($email) {
  global $db;

  $match = 0;

  $query = $db -> query("SELECT `id` FROM `users` WHERE `email` = ?s ", $email);
  if ($db -> numRows($query) > 0) 
    $match++;

  $baseEmail = array('yandex.com', 'yandex.ua', 'yandex.kz', 'yandex.by', 'ya.ru', 'yandex.ru');

  $emailShort = explode('@', $email);

  $nameEmail = $emailShort[0];

  $domenAndZona = $emailShort[1];

  if (in_array($domenAndZona, $baseEmail)) {

    $match = 0;

    foreach ($baseEmail as $domenZona) {

      $newEmail = $nameEmail.'@'.$domenZona;

      $query = $db -> query("SELECT `id` FROM `users` WHERE `email` = ?s ", $newEmail);
      if ($db -> numRows($query) > 0) 
        $match++;

    }

  } 

  if ($match) 
    return false;
  else
    return true;
}