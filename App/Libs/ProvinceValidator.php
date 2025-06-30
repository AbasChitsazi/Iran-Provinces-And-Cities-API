<?php

namespace App\Libs;



use App\Services\ProvinceServices;
use App\Utilities\Response;

class ProvinceValidator
{
    private static $message;
    private static $status_code;

    public static function validateProvince($data)
    {
        if (is_array($data)) {
            $whitelist = ['name'];
            $array_key = array_keys($data);
            if (count($array_key) !== count($whitelist) || array_diff($array_key, $whitelist)) {
                self::$message = [
                    'Usage:',
                    'name : string(name)'
                ];
                self::$status_code = Response::HTTP_BAD_REQUEST;
                return false;
            }
        } else {
            self::$message = [
                'Usage:',
                'name : string(name)'
            ];
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        if (!preg_match('/^[a-zA-Z\s]+$/',$data['name']) || empty($data['name']) || mb_strlen($data['name']) < 3) {
            self::$message = "Name Must Be String and Atleaset 3 charcter";
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        if(ProvinceServices::isProvinceExistbyName($data['name'])){
            self::$message = "Province With Name {$data['name']} already Exist!";
            self::$status_code = Response::HTTP_CONFLICT;
            return false;
        }
        return true;

    }
    public static function getMessage()
    {
        return self::$message;
    }

    public static function getStatusCode()
    {
        return self::$status_code;
    }
}
