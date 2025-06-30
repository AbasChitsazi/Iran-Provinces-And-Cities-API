<?php


include_once __DIR__ . "/../../../loader.php";

use App\Services\CityServices;
use App\Utilities\Response;
use App\Libs\CityValidator;

$Response = new Response();
$cityServices = new CityServices();


$request_method = $_SERVER['REQUEST_METHOD'];

$request_body = json_decode(file_get_contents('php://input'), true);


switch ($request_method) {
    case 'GET':
        $provinc_id = $_GET['province_id'] ?? null;
        $request_data = [
            'province_id' => $provinc_id
        ];
        if(!is_null($request_data['province_id'])){
            if (!CityValidator::isValidCity($request_data)) {
                Response::RespondeAndDie(CityValidator::$message, CityValidator::$status_code);
            }
        }
        $response = $cityServices->getAll($request_data);
        Response::RespondeAndDie($response, Response::HTTP_OK);
        break;
    case 'POST':
        Response::RespondeAndDie($request_body, Response::HTTP_OK);
        break;
    case 'DELETE':

        break;
    case 'PUT':

        break;
    default:
        Response::RespondeAndDie(['Invalid Request Method'], Response::HTTP_METHOD_NOT_ALLOWED);
        break;
}
