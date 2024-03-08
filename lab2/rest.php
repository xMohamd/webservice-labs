<?php

require_once 'vendor/autoload.php';

$urlParts = explode('/', $_SERVER['REQUEST_URI']);
$resource = $urlParts[4];
$resourceId = (isset($urlParts[5]) && is_numeric($urlParts[5])) ? (int) $urlParts[5] : 0;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $data = handleGet($resource, $resourceId);
        break;
    case 'POST':
        $data = handlePost($resource);
        break;
    default:
        http_response_code(405);
        return ["Error" => "Method not allowed"];
        break;
}


header('Content-Type: application/json');

if (!empty($data)) {
    echo json_encode($data);
}

function handleGet($resource, $resourceId)
{

    $db = new DBHandler;
    $item = array();

    if ($resource == "item") {
        if ($db->connect()) {
            $item = $db->get_record_by_id($resourceId, "id");
        } else {
            http_response_code(500);
            return ["Error" => "Internal server error"];
        }


        $res = ["msg" => "Success", "Items" => $item];

        if (empty($item)) {
            return ["error" => "Item not found"];
        }

        return $res;
    } else {
        http_response_code(404);
        return ["Error" => "Resource does not exist"];
    }
}

function handlePost($resource)
{

    $db = new DBHandler;

    if ($resource == "item") {
        if ($db->connect()) {

            $item = new Items();
            try {
                $item->id = $_POST["id"];
                $item->product_name = $_POST["product_name"];
                $item->PRODUCT_code = $_POST["PRODUCT_code"];
                $item->list_price = $_POST["list_price"];


                $item->save();
                return ["msg" => "Success: product added "];
            } catch (\Exception $ex) {
                return ["msg" => "fail: " . $ex->getMessage()];
            }
        } else {
            http_response_code(500);
            return ["Error" => "Internal server error"];
        }
    } else {
        http_response_code(404);
        return ["Error" => "Resource does not exist"];
    }
}
