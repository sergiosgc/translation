<?php declare(strict_types=1);
namespace sergiosgc\translation;

class RedisCache extends AbstractCache {
    const KEY_PREFIX = "sergiosgc\\translation\\";
    public ?\Redis $redis = null;
    public function __construct($host, $port) {
        $this->redis = new \Redis();
        $success = $this->redis->connect($host, $port);
        if (!$success) throw new Exception('Unable to connect to Redis cache');
    }
    public function __destruct() { if ($this->redis) $this->redis->close(); }
    public function _get(string $key) {
        if ($key == 'en') return [];
        if (isset($this->memoization[$key])) return $this->memoization[$key];
        $result = $this->redis->get(static::KEY_PREFIX . $key);
        if ($result === false) return null;
        $result = json_decode($result, true);
        if (is_null($result)) return null;
        $this->memoization[$key] = $result;
        return $result;
    }
    public function purge(string $key): void { }
    public function set(string $key, $value, int $lifetime = 24 * 60 * 60) {
        $this->redis->setEx(static::KEY_PREFIX . $key, $lifetime, json_encode($value));
    }
}
