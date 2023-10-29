<?php

namespace App\Services\Utilities;

enum HttpCodes: string
{
    case Ok = "200";
    case Created = "201";
    case BadRequest = "400";
    case UnAuthorized = "401";
    case Forbidden = "403";
    case NotFound = "404";
    case MethodNotAllowed = "405";
    case NotAcceptable = "406";
    case RequestTimeOut = "408";
    case Conflict = "409";
    case ServiceUnavailable = "503";
    case UnprocessableEntity = "422";
    case TooManyRequest = "429";
}
