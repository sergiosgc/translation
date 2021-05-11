<?php declare(strict_types=1);
namespace sergiosgc\translation;

class NoopCache extends AbstractCache {
    public function _get(string $key) { return null; }
    public function purge(string $key): void { }
    public function set(string $key, $value, int $lifetime = 24 * 60 * 60) { }
}