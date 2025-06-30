<?php 

namespace App\Config;

if(!defined('Auth_Access')){
    die("Access Denied");
}


use PDO;
use PDOException;

class dbConnection
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            $dsn = "mysql:host=localhost;dbname=iran;charset=utf8mb4";
            try {
                self::$pdo = new PDO($dsn, 'root', '');
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        return self::$pdo;
    }
}