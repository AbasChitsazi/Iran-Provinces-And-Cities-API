<?php


namespace App\Libs;


use App\Utilities\Response;
use App\Services\CityServices;
use App\Services\ProvinceServices;

class CityValidator
{
    private static $message;
    private static $status_code;
    public static function isValidProvinceid($data)
    {
        if (!is_numeric($data['province_id'])) {
            self::$message = "Province id Must Be Number";
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        $records =  CityServices::isCityExistWithProvinceid($data['province_id']);
        if (!$records) {
            self::$message = "city with province id {$data['province_id']} Not Exist!";
            self::$status_code = Response::HTTP_NOT_FOUND;
            return false;
        }
        return true;
    }
    public static function validateCityData($data)
    {
        if (is_array($data)) {
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
        if (!is_int($data['province_id']) || $data['province_id'] <= 0 || is_null($data['province_id'])) {
            self::$message = "Province id Must Be Number and Greater Than Zero";
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        if (!ProvinceServices::isProvinceExistbyId($data['province_id'])) {
            self::$message = 'Province With this Province_id does not Exist!';
            self::$status_code = Response::HTTP_NOT_FOUND;
            return false;
        }
        if (!preg_match('/^[\p{Arabic}a-zA-Z\s]+$/u', $data['name']) || empty($data['name']) || mb_strlen($data['name']) < 2) {
            self::$message = "Name Must Be String and Atleaset 2 charcter";
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        if (CityServices::isCityExistbyName($data['name'], $data['province_id'])) {
            $province = ProvinceServices::getrowbyId($data['province_id']);
            self::$message = "City With name {$data['name']} For Province {$province->name} Already Exist";
            self::$status_code = Response::HTTP_CONFLICT;
            return false;
        }

        $data['name'] = htmlspecialchars($data['name'], ENT_QUOTES, "UTF-8");
        return true;
    }
    public static function validationForDeleteCity($id)
    {
        $city_id = (int)$id['city_id'];
        if (!is_int($city_id) || $city_id == 0) {
            self::$message = "City_id Must Be Number And Greater Than Zero";
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        if (!CityServices::isCityExistById($city_id)) {
            self::$message = "City With id {$city_id} Not Exist!";
            self::$status_code = Response::HTTP_NOT_FOUND;
            return false;
        }
        return true;
    }
    public static function validationForUpdateCity($data)
    {
        if(is_array($data)){
            $whitelist = ['city_id', 'name'];
            $array_key = array_keys($data);
            if (count($array_key) !== count($whitelist) || array_diff($array_key, $whitelist)) {
                self::$message = [
                    'Usage:',
                    'city_id : int(id)',
                    'name : string(name)'
                ];
                self::$status_code = Response::HTTP_BAD_REQUEST;
                return false;
            }
        } else {
            self::$message = [
                'Usage:',
                'city_id : int(id)',
                'name : string(name)'
            ];
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        if (!is_int($data['city_id']) || $data['city_id'] == 0 || is_null($data['city_id'])) {
            self::$message = "City_id Must Be Number And Greater Than Zero";
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        if (!preg_match('/^[\p{Arabic}a-zA-Z\s]+$/u', $data['name']) || empty($data['name']) || mb_strlen($data['name']) < 2) {
            self::$message = "Name Must Be String and Atleaset 2 charcter";;
            self::$status_code = Response::HTTP_BAD_REQUEST;
            return false;
        }
        if(!CityServices::isCityExistById($data['city_id'])){
            self::$message = "City With id {$data['city_id']} Not Exist!";
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
