<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Redis;
use RedisException;
use Exception;

class ConnectorFacade
{
    protected $connector;

    public function __construct(string $host, int $port = 6379, ?string $password = null, int $dbindex = 0)
    {
        $redis = new Redis();

        try {
            $redis->connect($host,  $port);

            if ($password) {
                $redis->auth($password);
            }
            
            $redis->select($dbindex);
            $this->connector = new Connector($redis);

        } catch (RedisException) {
            throw new Exception('Failed to connect to the redis');
        }
    }
}
