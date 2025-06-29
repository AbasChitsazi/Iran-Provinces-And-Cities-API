<?php
namespace App\Services;

if(!defined('Auth_Access')){
    die("Access Denied");
}

class CityServices extends BaseService
{
    public function GetCities($data)
    {
        echo json_encode("hi");
    }
}