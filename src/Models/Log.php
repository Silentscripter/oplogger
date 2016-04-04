<?php

namespace Protechstudio\Oplogger\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{

    protected $fillable = ['user_id', 'operation', 'ip'];
}
