<?php

include_once __DIR__ . "/../../../loader.php";


use App\Services\ProvinceServices;
use App\Utilities\Response;



$request_method = $_SERVER['REQUEST_METHOD'];

$Response = new Response();
$provinceservice = new ProvinceServices();

switch ($request_method) {
    case 'GET':
        $response = $provinceservice->getAll();
        Response::RespondeAndDie($response,Response::HTTP_OK);
        break;
    case 'POST':

        break;
    case 'DELETE':

        break;
    case 'PUT':

        break;
    default:
        Response::RespondeAndDie(['Invalid Request Method'],Response::HTTP_METHOD_NOT_ALLOWED);
        break;
}
