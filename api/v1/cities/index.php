<?php


include_once __DIR__ . "/../../../loader.php";


use App\Services\CityServices;
use App\Utilities\Response;
use App\Libs\CityValidator;
use App\Libs\UserValidator;
use App\Utilities\CacheUtility;


$token = UserValidator::getBearerToken();
if (!$token || !UserValidator::decodeApiKey($token)) {
    Response::RespondeAndDie('Unauthorized, Invalid Token', Response::HTTP_UNAUTHORIZED);
}
$user = UserValidator::decodeApiKey($token);


$Response = new Response();
$cityServices = new CityServices();


$request_method = $_SERVER['REQUEST_METHOD'];

$request_body = json_decode(file_get_contents('php://input'), true);


switch ($request_method) {
    case 'GET':
        CacheUtility::start();
        $province_id = $_GET['province_id'] ?? null;
        $page = $_GET['page'] ?? null;
        $pagesize = $_GET['pagesize'] ?? null;
        $fields = $_GET['fields'] ?? '*';
        $order_by = $_GET['order_by'] ?? null;

        $request_data = compact('province_id', 'page', 'pagesize', 'fields', 'order_by');

        if (!CityValidator::isValidParameter($request_data)) {
            Response::RespondeAndDie(CityValidator::getMessage(), CityValidator::getStatusCode());
        }
        if(!UserValidator::hasAccess($user,$request_data['province_id'])){
            Response::RespondeAndDie("User {$user->username} has No Access to This Province",Response::HTTP_FORBIDDEN );
        }
        $response = $cityServices->getAll($request_data);

        if (empty($response))
            Response::RespondeAndDie('', Response::HTTP_NOT_FOUND);

        echo Response::responde($response, Response::HTTP_OK);
        CacheUtility::end();
        break;
    case 'POST':
        if(!UserValidator::hasPrivilage($user)){
            Response::RespondeAndDie("User {$user->username} has No Access to add City",Response::HTTP_FORBIDDEN );
        }
        if (!CityValidator::validateCityData($request_body))
            Response::RespondeAndDie(CityValidator::getMessage(), CityValidator::getStatusCode());

        if($cityServices->create($request_body)){
            CacheUtility::flush();
            Response::RespondeAndDie($request_body, Response::HTTP_CREATED);
        }

        break;
    case 'DELETE':
        if(!UserValidator::hasPrivilage($user)){
            Response::RespondeAndDie("User {$user->username} has No Access to Delete city",Response::HTTP_FORBIDDEN );
        }
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
        if(CityServices::deleteRow($request_data['city_id'])){
            CacheUtility::flush();
            Response::RespondeAndDie("City With id {$request_data['city_id']} deleted successfully.", Response::HTTP_OK);
        }
        break;
    case 'PUT':
        if(!UserValidator::hasPrivilage($user)){
            Response::RespondeAndDie("User {$user->username} has No Access to Update city",Response::HTTP_FORBIDDEN );
        }
        if (!CityValidator::validationForUpdateCity($request_body)) {
            Response::RespondeAndDie(CityValidator::getMessage(), CityValidator::getStatusCode());
        }
        if($cityServices->update($request_body['city_id'], $request_body['name'])){
            CacheUtility::flush();
            Response::RespondeAndDie("City Name With id {$request_body['city_id']} Change successfully to {$request_body['name']}", Response::HTTP_OK);
        }
        break;
    default:
        Response::RespondeAndDie(['Invalid Request Method'], Response::HTTP_METHOD_NOT_ALLOWED);
        break;
}
