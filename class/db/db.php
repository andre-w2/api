<?php 

session_start();
define('DIR', $_SERVER['DOCUMENT_ROOT']);

include_once './system/lib/mysqli.class.php';
include_once './system/lib/navigation.php';

class Database {
	private const DB_HOST='localhost';
	private const DB_USER='root';
	private const DB_PASS='12345';
	private const DB_BASE='vue';
	public $conn;

    public function getConnection(){
        $this->conn = new SafeMySQL(array('user' => self::DB_USER, 'pass' => self::DB_PASS,'db' => self::DB_BASE, 'charset' => 'utf8'));

        return $this->conn;
    }
}

?>