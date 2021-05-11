<?php declare(strict_types=1);
namespace sergiosgc\translation;

interface ICache {
    public function get(string $key, ?callable $getter);
    public function purge(string $key): void;
    public function set(string $key, $value, int $lifetime = 24 * 60 * 60);
}
