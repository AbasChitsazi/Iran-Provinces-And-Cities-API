<?php
namespace App\Services;

if(!defined('Auth_Access')){
    die("Access Denied");
}

abstract class BaseService{
    protected $Primery_Key = "id";
    

}