<?php


namespace App\Libs;


use App\Utilities\Response;
use App\Services\CityServices;
use App\Services\ProvinceServices;

class CityValidator
{
    private static $message;
    private static $status_code;
    public static function isValidParameter(& $data)
    {
        if (!empty($data['province_id'])) {
            if (!ctype_digit((string)$data['province_id'])) {
                self::$message = "Province ID must be a numeric integer";
                self::$status_code = Response::HTTP_BAD_REQUEST;
                return false;
            }

            if (!CityServices::isExist($data['province_id'], 'province_id')) {
                self::$message = "No cities found for province ID {$data['province_id']}";
                self::$status_code = Response::HTTP_NOT_FOUND;
                return false;
            }
        }
        if (!empty($data['page']) || !empty($data['pagesize'])) {
            if (!ctype_digit((string)$data['page']) || !ctype_digit((string)$data['pagesize'])) {
                self::$message = "Page and PageSize must be numeric integers";
                self::$status_code = Response::HTTP_BAD_REQUEST;
                return false;
            }
        }
        if (($data['fields'] !== '*')) {
            if (!empty($data['fields'])) {

                if (!preg_match('/^[a-zA-Z_,]+$/', $data['fields'])) {
                    self::$message = "Only Use letters and comma (,) to separate fields";
                    self::$status_code = Response::HTTP_BAD_REQUEST;
                    return false;
                }


                $fields = explode(',', $data['fields']);


                foreach ($fields as $field) {
                    if (empty($field)) {
                        self::$message = "Invalid format for fields. Do not use empty values or extra commas.";
                        self::$status_code = Response::HTTP_BAD_REQUEST;
                        return false;
                    }
                }
                $whitelist = ['id', 'name', 'province_id'];
                foreach ($fields as $field) {
                    if (!in_array($field, $whitelist)) {
                        $whitelitfortxt = implode(',', $whitelist);
                        self::$message = "Fields can only contain: {$whitelitfortxt}";
                        self::$status_code = Response::HTTP_BAD_REQUEST;
                        return false;
                    }
                }


                $data['fields'] = implode(',',$fields);
            } else {
                self::$message = "Fields can only contain: id, name, province_id";
                self::$status_code = Response::HTTP_BAD_REQUEST;
                return false;
            }
        }
        if (!empty($data['order_by'])) {
            $whitelist_fields = ['id', 'name'];
            $whitelist_directions = ['ASC', 'DESC'];

            $parts = explode(',', $data['order_by']);

            if (count($parts) !== 2) {
                self::$message = "order_by must be in the format: field,ASC|DESC";
                self::$status_code = Response::HTTP_BAD_REQUEST;
                return false;
            }

            [$field, $direction] = $parts;

            if (!in_array($field, $whitelist_fields)) {
                self::$message = "order_by field must be one of: " . implode(', ', $whitelist_fields);
                self::$status_code = Response::HTTP_BAD_REQUEST;
                return false;
            }

            if (!in_array(strtoupper($direction), $whitelist_directions)) {
                self::$message = "order_by direction must be ASC or DESC";
                self::$status_code = Response::HTTP_BAD_REQUEST;
                return false;
            }


            $data['order_by'] = " ORDER BY " . $field . ' ' . strtoupper($direction);
            
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
            self::$status_code = Response::HTTP_NOT_ACCEPTABLE;
            return false;
        }
        if (!ProvinceServices::isExist($data['province_id'])) {
            self::$message = 'Province With this Province_id does not Exist!';
            self::$status_code = Response::HTTP_NOT_FOUND;
            return false;
        }
        if (!preg_match('/^[\p{Arabic}a-zA-Z\s]+$/u', $data['name']) || empty($data['name']) || mb_strlen($data['name']) < 2) {
            self::$message = "Name Must Be String and Atleaset 2 charcter";
            self::$status_code = Response::HTTP_NOT_ACCEPTABLE;
            return false;
        }
        if (CityServices::isCityExistByNameAndProvince($data['name'], $data['province_id'])) {
            $province = ProvinceServices::getRow($data['province_id']);
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
            self::$status_code = Response::HTTP_NOT_ACCEPTABLE;
            return false;
        }
        if (!CityServices::isExist($city_id)) {
            self::$message = "City With id {$city_id} Not Exist!";
            self::$status_code = Response::HTTP_NOT_FOUND;
            return false;
        }
        return true;
    }
    public static function validationForUpdateCity($data)
    {
        if (is_array($data)) {
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
            self::$status_code = Response::HTTP_NOT_ACCEPTABLE;
            return false;
        }
        if (!preg_match('/^[\p{Arabic}a-zA-Z\s]+$/u', $data['name']) || empty($data['name']) || mb_strlen($data['name']) < 2) {
            self::$message = "Name Must Be String and Atleaset 2 charcter";;
            self::$status_code = Response::HTTP_NOT_ACCEPTABLE;
            return false;
        }
        if (!CityServices::isExist($data['city_id'])) {
            self::$message = "City With id {$data['city_id']} Not Exist!";
            self::$status_code = Response::HTTP_NOT_FOUND;
            return false;
        }
        if (CityServices::getRow($data['city_id'])->name == $data['name']) {
            self::$message = "City With id {$data['city_id']} Alreay is {$data['name']}";
            self::$status_code = Response::HTTP_CONFLICT;
            return false;
        }
        $data['name'] = htmlspecialchars($data['name'], ENT_QUOTES, "UTF-8");
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
