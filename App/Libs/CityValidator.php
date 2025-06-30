<?php


namespace App\Libs;

use App\Config\dbConnection;
use App\Utilities\Response;

class CityValidator
{
    public static $message;
    public static $status_code;
    public static function isValidCity($id)
    {
        if(!is_numeric($id['province_id'])){
            self::$message = "Province id Must Be Number";
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        $pdo = dbConnection::getConnection();
        $sql = "SELECT COUNT(*) FROM city WHERE province_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id['province_id']]);
        $records =  $stmt->fetchColumn() > 0 ;
        if(!$records){
            self::$message = "city with province id {$id['province_id']} Not Exist!";
            self::$status_code = Response::HTTP_NOT_FOUND;
            return false;
        }
        return true;
        
    }
}