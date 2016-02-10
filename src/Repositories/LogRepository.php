<?php

namespace Protechstudio\Oplogger\Repositories;


use Protechstudio\Oplogger\Models\Log;

class LogRepository implements LogRepositoryContract
{

    /**
     * @var Log
     */
    private $model;

    public function __construct(Log $model)
    {
        $this->model = $model;
    }

    public function write($userID, $operation)
    {
        $this->model->create([
            'user_id' => $userID,
            'operation' => $operation
        ]);

        return true;
    }

}