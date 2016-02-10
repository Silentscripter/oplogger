<?php

namespace Protechstudio\Oplogger\Facades;


use Illuminate\Support\Facades\Facade;

class Oplogger extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Protechstudio\Oplogger\Oplogger::class;
    }

}