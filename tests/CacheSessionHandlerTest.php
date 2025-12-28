<?php

use Codemonster\Session\Handlers\CacheSessionHandler;
use Codemonster\Session\Store;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class CacheSessionHandlerTest extends TestCase
{
    public function testReadWriteAndDestroy(): void
    {
        $cache = new ArrayCache();
        $handler = new CacheSessionHandler($cache);
        $id = bin2hex(random_bytes(16));

        $store = new Store($handler, $id);
        $store->start();
        $store->put('token', '123');

        $store2 = new Store($handler, $id);
        $store2->start();

        $this->assertSame('123', $store2->get('token'));

        $handler->destroy($id);

        $this->assertSame('', $handler->read($id));
    }

    public function testRetryOnFailure(): void
    {
        $cache = new class extends ArrayCache {
            private int $tries = 0;
            public function set($key, $value, $ttl = null)
            {
                $this->tries++;
                if ($this->tries === 1) {
                    throw new \RuntimeException('fail');
                }
                return parent::set($key, $value, $ttl);
            }
        };

        $handler = new CacheSessionHandler($cache, 'sess_', 0, 1, 0);
        $this->assertTrue($handler->write('abc', 'value'));
    }
}

/**
 * @psalm-suppress MethodSignatureMismatch
 */
class ArrayCache implements CacheInterface
{
    /** @var array<string, mixed> */
    private array $data = [];

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|\DateInterval|null $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        $this->data[$key] = $value;

        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        unset($this->data[$key]);

        return true;
    }

    /**
     * @return bool
     */
    public function clear()
    {
        $this->data = [];

        return true;
    }

    /**
     * @param iterable<int, string> $keys
     * @param mixed $default
     * @return array<string, mixed>
     */
    public function getMultiple($keys, $default = null)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->data[$key] ?? $default;
        }

        return $result;
    }

    /**
     * @param iterable<string, mixed> $values
     * @param int|\DateInterval|null $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->data[$key] = $value;
        }

        return true;
    }

    /**
     * @param iterable<int, string> $keys
     * @return bool
     */
    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {
            unset($this->data[$key]);
        }

        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }
}
