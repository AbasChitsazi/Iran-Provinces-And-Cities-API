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
        $province_id = $_GET['province_id'] ?? null;
        $request_data = [
            'province_id' => $province_id
        ];
        if (!is_null($request_data['province_id'])) {
            if (!CityValidator::isValidProvinceid($request_data)) {
                Response::RespondeAndDie(CityValidator::getMessage(), CityValidator::getStatusCode());
            }
        }
        $response = $cityServices->getAll($request_data);
        if(empty($response)){
            Response::RespondeAndDie('', Response::HTTP_NOT_FOUND);
        }
        Response::RespondeAndDie($response, Response::HTTP_OK);
        break;
    case 'POST':
        if (!CityValidator::validateCityData($request_body)) {
            Response::RespondeAndDie(CityValidator::getMessage(), CityValidator::getStatusCode());
        }
        if($cityServices->create($request_body)){
            Response::RespondeAndDie($request_body,Response::HTTP_CREATED);
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
