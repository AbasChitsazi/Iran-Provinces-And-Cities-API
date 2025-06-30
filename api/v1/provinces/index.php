<?php

include_once __DIR__ . "/../../../loader.php";

use App\Libs\ProvinceValidator;
use App\Services\ProvinceServices;
use App\Services\CityServices;
use App\Utilities\Response;



$request_method = $_SERVER['REQUEST_METHOD'];

$request_body = json_decode(file_get_contents('php://input'), true);

$Response = new Response();
$provinceservice = new ProvinceServices();
$cityservice = new CityServices();

switch ($request_method) {
    case 'GET':
        $response = $provinceservice->getAll();
        if (empty($response)) {
            Response::RespondeAndDie('', Response::HTTP_NOT_FOUND);
        }
        Response::RespondeAndDie($response, Response::HTTP_OK);
        break;
    case 'POST':
        if(!ProvinceValidator::validateProvince($request_body)){
            Response::RespondeAndDie(ProvinceValidator::getMessage(), ProvinceValidator::getStatusCode());
        }

        if($provinceservice->create($request_body)){
            $row = $provinceservice::getrowbyName($request_body['name']);
            if($cityservice->create(['province_id'=>$row->id,'name'=>$request_body['name']])){
                Response::RespondeAndDie($request_body, Response::HTTP_CREATED);
            }else{
                // DELETE PROVINCE WHEN CREATING CITY FAILED 
            }

        }
        break;
    case 'DELETE':

        break;
    case 'PUT':

        break;
    default:
        Response::RespondeAndDie(['Invalid Request Method'], Response::HTTP_METHOD_NOT_ALLOWED);
        break;
}
