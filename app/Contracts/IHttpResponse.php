<?php

namespace App\Contracts;

use App\Contracts\ResponseData;

interface IHttpResponse
{
    public static function json(ResponseData $response);
}
