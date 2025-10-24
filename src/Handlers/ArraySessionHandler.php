<?php

namespace Codemonster\Session\Handlers;

use SessionHandlerInterface;

class ArraySessionHandler implements SessionHandlerInterface
{
    protected array $storage = [];

    public function open(string $savePath, string $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        return isset($this->storage[$id])
            ? json_encode($this->storage[$id], JSON_UNESCAPED_UNICODE)
            : '';
    }

    public function write(string $id, string $data): bool
    {
        $this->storage[$id] = json_decode($data, true) ?? [];

        return true;
    }

    public function destroy(string $id): bool
    {
        unset($this->storage[$id]);

        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        return 0;
    }
}
