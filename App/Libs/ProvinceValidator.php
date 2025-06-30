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
        if (!preg_match('/^[\p{Arabic}a-zA-Z\s]+$/u', $data['name']) || empty($data['name']) || mb_strlen($data['name']) < 3) {
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
    public static function validationForDeleteProvince($data)
    {
        $province_id = (int)$data['province_id'];
        if (!is_int($province_id ) || $province_id == 0) {
            self::$message = "Province_id Must Be Number And Greater Than Zero";
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        if (!ProvinceServices::isProvinceExistbyId($province_id)) {
            self::$message = "Province With id {$province_id} Not Exist!";
            self::$status_code = Response::HTTP_NOT_FOUND;
            return false;
        }
        return true;
    }
        public static function validationForUpdateProvince($data)
    {
        if(is_array($data)){
            $whitelist = ['province_id', 'name'];
            $array_key = array_keys($data);
            if (count($array_key) !== count($whitelist) || array_diff($array_key, $whitelist)) {
                self::$message = [
                    'Usage:',
                    'province_id : int(id)',
                    'name : string(name)'
                ];
                self::$status_code = Response::HTTP_BAD_REQUEST;
                return false;
            }
        } else {
            self::$message = [
                'Usage:',
                'province_id : int(id)',
                'name : string(name)'
            ];
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        if (!is_int($data['province_id']) || $data['province_id'] == 0 || is_null($data['province_id'])) {
            self::$message = "province_id Must Be Number And Greater Than Zero";
            self::$status_code = Response::HTTP_NOT_FOUND;
            return false;
        }
        if (!preg_match('/^[\p{Arabic}a-zA-Z\s]+$/u', $data['name']) || is_null($data['name']) || mb_strlen($data['name']) < 2) {
            self::$message = "Name Must Be String and Atleaset 2 charcter";;
            self::$status_code = Response::HTTP_NOT_FOUND;
            return false;
        }
        if(!ProvinceServices::isProvinceExistbyId($data['province_id'])){
            self::$message = "Province With id {$data['province_id']} Not Exist!";
            self::$status_code = Response::HTTP_NOT_FOUND;
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
