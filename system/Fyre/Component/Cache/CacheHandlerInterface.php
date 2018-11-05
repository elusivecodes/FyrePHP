<?php

namespace Fyre\Component\Cache;

interface CacheHandlerInterface
{

    public function decrement(string $key, int $amount = 1): int;
    public function delete(string $key = ''): bool;
    public function forget(string $key): bool;
    public function get(string $key);
    public function has(string $key): bool;
    public function increment(string $key, int $amount = 1): int;
    public function save(string $key, $data, int $expire = 0): bool;
    public function size(string $key = ''): ?int;

}
