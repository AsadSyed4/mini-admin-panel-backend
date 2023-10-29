<?php

namespace App\Contracts;

use App\Services\Utilities\HttpCodes;

class ResponseData
{
    public string $message;
    public HttpCodes $status_code;

    public array|string|int|null $data;

    public function __construct(string $message, HttpCodes $statusCode, array|string|int|null|object $data)
    {
        $this->message = $message;
        $this->status_code = $statusCode;
        $this->data = $data;
    }
}
