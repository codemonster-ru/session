<?php

namespace Codemonster\Session;

use Codemonster\Session\Handlers\ArraySessionHandler;
use Codemonster\Session\Handlers\FileSessionHandler;
use SessionHandlerInterface;

class Session
{
    protected static ?Store $store = null;

    public static function start(
        string $driver = 'file',
        array $options = [],
        ?SessionHandlerInterface $customHandler = null
    ): void {
        if ($customHandler) {
            $handler = $customHandler;
        } elseif ($driver === 'array') {
            $handler = new ArraySessionHandler();
        } else {
            $savePath = $options['path'] ?? sys_get_temp_dir() . '/sessions';
            $handler = new FileSessionHandler($savePath);
        }

        static::$store = new Store($handler);
        static::$store->start();
    }

    public static function store(): Store
    {
        if (!static::$store) {
            static::start();
        }

        return static::$store;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::store()->get($key, $default);
    }

    public static function put(string $key, mixed $value): void
    {
        static::store()->put($key, $value);
    }

    public static function forget(string $key): void
    {
        static::store()->forget($key);
    }

    public static function all(): array
    {
        return static::store()->all();
    }

    public static function destroy(): void
    {
        static::store()?->destroy();
    }
}
