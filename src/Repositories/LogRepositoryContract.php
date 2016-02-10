<?php

namespace Protechstudio\Oplogger\Repositories;


interface LogRepositoryContract
{

    function write($userID, $operation);
}