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
        if (!ProvinceValidator::validateProvince($request_body)) {
            Response::RespondeAndDie(ProvinceValidator::getMessage(), ProvinceValidator::getStatusCode());
        }

        if ($provinceservice->create($request_body)) {
            $row = $provinceservice::getrowbyName($request_body['name']);
            if ($cityservice->create(['province_id' => $row->id, 'name' => $request_body['name']])) {
                Response::RespondeAndDie($request_body, Response::HTTP_CREATED);
            } else {
                // DELETE PROVINCE WHEN CREATING CITY FAILED 
            }
        }
        break;
    case 'DELETE':
        $province_id = $_GET['province_id'] ?? null;
        $request_data = [
            'province_id' => $province_id
        ];
        if (!isset($province_id)) {
            Response::RespondeAndDie("province_id Must Be set", Response::HTTP_BAD_REQUEST);
        }
        if (!is_null($request_data['province_id'])) {
            if (!ProvinceValidator::validationForDeleteProvince($request_data))
                Response::RespondeAndDie(ProvinceValidator::getMessage(), ProvinceValidator::getStatusCode());
        }
        $provinceservice->delete($request_data['province_id']);
        $cityservice::deleteWithProvinceId($request_data['province_id']);
        Response::RespondeAndDie("Province With id {$request_data['province_id']} And All sub cities deleted successfully.", Response::HTTP_OK);
        break;
    case 'PUT':
        if (!ProvinceValidator::validationForUpdateProvince($request_body)) {
            Response::RespondeAndDie(ProvinceValidator::getMessage(), ProvinceValidator::getStatusCode());
        }
        $city_data = $cityservice::getProvince($request_body['province_id']);
        if (!empty($city_data)) {
            $cityservice->update($city_data->id, $request_body['name']);
            $provinceservice->update($request_body['province_id'], $request_body['name']);
        }


        Response::RespondeAndDie("Province Name With id {$request_body['province_id']} Change successfully to {$request_body['name']}", Response::HTTP_OK);
        break;
        break;
    default:
        Response::RespondeAndDie(['Invalid Request Method'], Response::HTTP_METHOD_NOT_ALLOWED);
        break;
}
