<?php

include_once __DIR__ . "/../../../loader.php";

use App\Libs\ProvinceValidator;
use App\Services\ProvinceServices;
use App\Services\CityServices;
use App\Utilities\Response;
use App\Utilities\CacheUtility;
use App\Libs\UserValidator;

$token = UserValidator::getBearerToken();
if (!$token || !UserValidator::decodeApiKey($token)) {
    Response::RespondeAndDie('Unauthorized, Invalid Token', Response::HTTP_UNAUTHORIZED);
}
$user = UserValidator::decodeApiKey($token);

$request_method = $_SERVER['REQUEST_METHOD'];

$request_body = json_decode(file_get_contents('php://input'), true);

$Response = new Response();
$provinceservice = new ProvinceServices();
$cityservice = new CityServices();

switch ($request_method) {
    case 'GET':
        CacheUtility::start();
        if(!UserValidator::hasPrivilage($user)){
            $province = $user->allowed_province;
            $province = implode(',',$province);
            $where = "WHERE id IN({$province})";
            $response = $provinceservice->getAll($where);
            if (empty($response)) {
                Response::RespondeAndDie('', Response::HTTP_NOT_FOUND);
            }
            echo Response::responde($response, Response::HTTP_OK);
            CacheUtility::end();
            break;
        }
        $response = $provinceservice->getAll();

        if (empty($response)) {
            Response::RespondeAndDie('', Response::HTTP_NOT_FOUND);
        }
        echo Response::responde($response, Response::HTTP_OK);
        CacheUtility::end();
        break;
    case 'POST':
        if(!UserValidator::hasPrivilage($user)){
            Response::RespondeAndDie("User {$user->username} has No Access to ADD City",Response::HTTP_FORBIDDEN );
        }
        if (!ProvinceValidator::validateProvinceForCreate($request_body)) {
            Response::RespondeAndDie(ProvinceValidator::getMessage(), ProvinceValidator::getStatusCode());
        }

        if ($provinceservice->create($request_body)) {
            $row = ProvinceServices::getRow($request_body['name'],'name');
            if ($cityservice->create(['province_id' => $row->id, 'name' => $request_body['name']])) {
                CacheUtility::flush();
                Response::RespondeAndDie($request_body, Response::HTTP_CREATED);
            } else {
                $provinceservice::deleteRow($request_body['name'],'name');
                Response::RespondeAndDie("Create Province Failed Please Try Again Later.",Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        break;
    case 'DELETE':
        if(!UserValidator::hasPrivilage($user)){
            Response::RespondeAndDie("User {$user->username} has No Access to DELETE City",Response::HTTP_FORBIDDEN );
        }
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
        if(ProvinceServices::deleteRow($request_data['province_id']) && CityServices::deleteRow($request_data['province_id'],'province_id')){
                CacheUtility::flush();
                Response::RespondeAndDie("Province With id {$request_data['province_id']} And All sub cities deleted successfully.", Response::HTTP_OK);
        }
        break;
    case 'PUT':
        if (!ProvinceValidator::validationForUpdateProvince($request_body)) {
            Response::RespondeAndDie(ProvinceValidator::getMessage(), ProvinceValidator::getStatusCode());
        }
        $city_data = ProvinceServices::getProvince($request_body['province_id']);
        if (!empty($city_data)) {
            $cityservice->update($city_data->id, $request_body['name']);
            $provinceservice->update($request_body['province_id'], $request_body['name']);
            CacheUtility::flush();
        }

        Response::RespondeAndDie("Province Name With id {$request_body['province_id']} Change successfully to {$request_body['name']}", Response::HTTP_OK);
        break;
    default:
        Response::RespondeAndDie(['Invalid Request Method'], Response::HTTP_METHOD_NOT_ALLOWED);
        break;
}
