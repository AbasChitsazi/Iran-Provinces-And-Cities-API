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
    $page = $_GET['page'] ?? null;
    $pagesize = $_GET['pagesize'] ?? null;
    $fields = $_GET['fields'] ?? '*';
    $order_by = $_GET['order_by'] ?? null ;

    $request_data = compact('province_id', 'page', 'pagesize','fields','order_by');

    if (!CityValidator::isValidParameter($request_data)) {
        Response::RespondeAndDie(CityValidator::getMessage(), CityValidator::getStatusCode());
    }
    
    $response = $cityServices->getAll($request_data);

    if (empty($response))
        Response::RespondeAndDie('', Response::HTTP_NOT_FOUND);

    Response::RespondeAndDie($response, Response::HTTP_OK);
        break;
    case 'POST':
        if (!CityValidator::validateCityData($request_body))
            Response::RespondeAndDie(CityValidator::getMessage(), CityValidator::getStatusCode());

        $cityServices->create($request_body);
        Response::RespondeAndDie($request_body, Response::HTTP_CREATED);

        break;
    case 'DELETE':
        $city_id = $_GET['city_id'] ?? null;
        $request_data = [
            'city_id' => $city_id
        ];
        if (!isset($city_id)) {
            Response::RespondeAndDie("City_id Must Be set", Response::HTTP_BAD_REQUEST);
        }
        if (!is_null($request_data['city_id'])) {
            if (!CityValidator::validationForDeleteCity($request_data))
                Response::RespondeAndDie(CityValidator::getMessage(), CityValidator::getStatusCode());
        }
        CityServices::deleteRow($request_data['city_id']);
        Response::RespondeAndDie("City With id {$request_data['city_id']} deleted successfully.", Response::HTTP_OK);
        break;
    case 'PUT':
        if (!CityValidator::validationForUpdateCity($request_body)) {
            Response::RespondeAndDie(CityValidator::getMessage(), CityValidator::getStatusCode());
        }
        $cityServices->update($request_body['city_id'], $request_body['name']);
        Response::RespondeAndDie("City Name With id {$request_body['city_id']} Change successfully to {$request_body['name']}", Response::HTTP_OK);
        break;
    default:
        Response::RespondeAndDie(['Invalid Request Method'], Response::HTTP_METHOD_NOT_ALLOWED);
        break;
}
