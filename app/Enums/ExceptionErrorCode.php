<?php

namespace App\Enums;

enum ExceptionErrorCode: int
{
    // not found: 404x;
    case API_ENDPOINT_NOT_FOUND = 4040;
    // authentication: 401x
    case AUTHENTICATION_FAILED = 4010;
    // Server: 50xx
    case INTERNAL_SERVER_ERROR = 5001;
}

?>