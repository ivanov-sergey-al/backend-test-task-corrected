<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Entity\Cart;
use Redis;
use RedisException;

class Connector
{
    public function __construct(private Redis $redis)
    {
    }

    public function get(string $key)
    {
        return unserialize($this->redis->get($key));
    }

    public function set(string $key, Cart $value): void
    {
        $this->redis->setex($key, 24 * 60 * 60, serialize($value));
    }

    public function has($key): bool
    {
        return $this->redis->exists($key);
    }
}
