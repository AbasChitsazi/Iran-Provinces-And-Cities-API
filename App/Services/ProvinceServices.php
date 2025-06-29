<?php

namespace App\Services;

if(!defined('Auth_Access')){
    die("Access Denied");
}


class ProvinceServices extends BaseServices
{

        public function getAll()
    {
        $pdo = self::db();
        $sql = "SELECT * FROM province";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll($pdo::FETCH_OBJ);
    }

    public function create($data)
    {
    
    }
}