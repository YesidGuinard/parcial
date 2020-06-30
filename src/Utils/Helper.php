<?php


namespace App\Utils;

use stdClass;

class Helper
{
    static function formatResponse($success,$message)
    {
        $response = new stdClass();
        $response->success = $success;
        $response->data = $message;
        return json_encode($response,JSON_UNESCAPED_UNICODE);
    }

}