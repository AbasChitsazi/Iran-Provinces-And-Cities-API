<?php


include_once __DIR__ . "/../../../loader.php";

use App\Services\CityServices;
use App\Utilities\Response;



$request_method = $_SERVER['REQUEST_METHOD'];

$Response = new Response();
$cityServices = new CityServices();



switch ($request_method) {
    case 'GET':
        $provinc_id = $_GET['province_id'] ?? null;
        $request_data = [
            'province_id' => $provinc_id
        ];
        $response = $cityServices->getAll($request_data);
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
