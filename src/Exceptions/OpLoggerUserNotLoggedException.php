<?php

namespace Protechstudio\Oplogger\Exceptions;

use Exception;

class OploggerUserNotLoggedException extends Exception
{

    protected $statusCode = 500;
}