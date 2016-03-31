<?php

namespace Protechstudio\Oplogger;

use Illuminate\Contracts\Auth\Guard;
use Protechstudio\Oplogger\Exceptions\OploggerKeyNotFoundException;
use Protechstudio\Oplogger\Exceptions\OploggerUserNotLoggedException;
use Protechstudio\Oplogger\Repositories\LogRepositoryContract;

class Oplogger
{

    /**
     * @var array
     */
    private $types;

    /**
     * @var Guard $auth
     */
    private $auth;

    /**
     * @var LogRepositoryContract
     */
    private $logRepository;


    /**
     * OpLogger constructor.
     * @param array $types
     * @param Guard $auth
     * @param LogRepositoryContract $logRepository
     */
    public function __construct(array $types, Guard $auth, LogRepositoryContract $logRepository)
    {
        $this->types = $types;
        $this->auth = $auth;
        $this->logRepository = $logRepository;
    }

    /**
     * Writes the log about the specific operation type presented with optional parameters
     * @param $opType string the operation type string
     * @param array $typeParams the optional parameters to be interpolate in the operation string
     * @param bool $userID the user id, will take the logged user id from Auth if not set
     * @return bool True if successful, throws an OpLoggerKeyNotFoundException otherwise
     * @throws OpLoggerKeyNotFoundException
     */
    public function write($opType, array $typeParams = [], $userID = false)
    {
        $userID = $this->getUserID($userID);
        $type = $this->getType($opType);
        $logString = vsprintf($type, $typeParams);
        $ip = $_SERVER['REMOTE_ADDR'];

        return $this->logRepository->write($userID, $logString, $ip);
    }

    /**
     * Get the specific operation type
     * @param $typeKey
     * @return string the type string fro logging
     * @throws OploggerKeyNotFoundException
     */
    private function getType($typeKey)
    {
        if (!array_has($this->types, $typeKey)) {
            throw new OploggerKeyNotFoundException;
        }

        return $this->types[$typeKey];
    }


    /**
     * Returns the user id either from the parameter or from the logged user
     * @param $userID
     * @return int the logged user id
     * @throws OploggerUserNotLoggedException
     */
    private function getUserID($userID)
    {
        if ($userID) {
            return $userID;
        }

        if ($this->auth->guest()) {
            throw new OploggerUserNotLoggedException;
        }

        return $this->auth->user()->getAuthIdentifier();
    }

}