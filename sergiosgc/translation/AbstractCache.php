<?php declare(strict_types=1);
namespace sergiosgc\translation;

abstract class AbstractCache implements ICache {
    public abstract function _get(string $key);
    public abstract function purge(string $key): void;
    public abstract function set(string $key, $value, int $lifetime = 24 * 60 * 60);
    public function get(string $key, ?callable $getter) {
        $result = $this->_get($key);
        if (!is_null($result)) return $result;
        $args = array_slice(func_get_args(), 2, null, false);
        $result = $getter(...$args);
        if (!is_null($result)) $this->set($key, $result);
        return $result;
    }
}
