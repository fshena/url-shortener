<?php

namespace ShortenEndpoint\Interfaces;

interface CacheInterface
{
    public function set($key, $value): void;

    public function get($key);

    public function checkConnection(): bool;
}
