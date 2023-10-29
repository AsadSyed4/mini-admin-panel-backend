<?php
namespace App\Services\Utilities;

use App\Contracts\IHttpResponse;
use App\Contracts\ResponseData;

class HttpResponse implements IHttpResponse
{
    public static function json(ResponseData $response)
    {
        return response()->json([
            'message' => $response->message,
            'data' => $response->data
        ], $response->status_code->value);
    }
}
