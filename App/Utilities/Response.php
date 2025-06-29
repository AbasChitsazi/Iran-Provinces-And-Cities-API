<?php

namespace App\Utilities;

if(!defined('Auth_Access')){
    die("Access Denied");
}

class Response{
    public static function responde($data,$status_code)
    {
        header("http 1/1 $status_code OK");
        header("Content-Type: Application/json");
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }
}