<?php

namespace App\Libs;

include_once __DIR__."/../../loader.php";

if (!defined('Auth_Access')) {
    die("Access Denied");
}

use Firebase\JWT\JWT;




class UserValidator{

    private static $alg = JWT_ALG;
    private static $key = JWT_SECRET;

    public static function Users():array{
        return  [
            (object)['id' => 1,'username' => 'admin','email' => 'chitsazi3@gmail.com','role' => 'admin','allowed_province' => []],
            (object)['id' => 2,'username' => 'ali','email' => 'user2@gmail.com','role' => 'president','allowed_province' => [1,2,3]],
            (object)['id' => 3,'username' => 'sara','email' => 'user3@gmail.com','role' => 'Mayor','allowed_province' => [20,22,25]]
        ];
    }
    public static function getUserbyId($id){
        foreach(self::Users() as $user){
            if($user->id == $id){
                return $user;
            }
        }
        return null;
    }
    public static function getUserbyEmail($email){
        foreach(self::Users() as $user){
            if($user->email == $email){
                return $user;
            }
        }
        return null;
    }

    public static function createApiKey($user){
        $payload = ['user_id'=>$user->id];
        $jwt = JWT::encode($payload, self::$key, self::$alg);
        return $jwt;
    }
    public static function decodeApiKey($jwt) {
        try {
            $decoded = JWT::decode($jwt, new \Firebase\JWT\Key(self::$key, self::$alg));
            $user = self::getUserbyId($decoded->user_id);
            return $user;
        } catch (\Exception $e) {
            return false;
        }
    }
    public static function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { 
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();

            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    

    public static function getBearerToken() {
        $headers = self::getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
    public static function hasAccess($user,$province_id){
        if(in_array($user->role,['admin','president'])){
            return true;
        }
        if(in_array($province_id,$user->allowed_province)){
            return true;
        }
        return false;
    }
    public static function hasPrivilage($user){
        if(in_array($user->role,['admin','president'])){
            return true;
        }
    }
    
}



