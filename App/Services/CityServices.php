<?php

namespace App\Services;

if (!defined('Auth_Access')) {
    die("Access Denied");
}




class CityServices extends BaseServices
{
    
    public  function getAll($data=null)
    {
        $where = '';
        $province_id = $data['province_id'] ?? null;
        if(!is_null($province_id) && is_numeric($province_id)){
            $where = "WHERE province_id = {$province_id}";
        }
        $pdo = self::db();
        $sql = "SELECT * FROM city $where";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll($pdo::FETCH_OBJ);
         
    }
    public function create($data)
    {
        
    }
}
