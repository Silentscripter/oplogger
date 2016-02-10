<?php

namespace Protechstudio\Oplogger\Exceptions;

use Exception;

class OploggerKeyNotFoundException extends Exception
{

    protected $statusCode = 500;
}