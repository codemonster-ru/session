<?php

namespace Codemonster\Session\Handlers;

use SessionHandlerInterface;

class FileSessionHandler implements SessionHandlerInterface
{
    public function __construct(protected string $path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

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
        $file = "{$this->path}/sess_{$id}";

        if (!file_exists($file)) {
            return '';
        }

        $contents = file_get_contents($file);

        return $contents === false ? '' : $contents;
    }

    public function write(string $id, string $data): bool
    {
        $file = "{$this->path}/sess_{$id}";

        return file_put_contents($file, $data) !== false;
    }

    public function destroy(string $id): bool
    {
        $file = "{$this->path}/sess_{$id}";

        return file_exists($file) ? unlink($file) : true;
    }

    public function gc(int $max_lifetime): int|false
    {
        $count = 0;

        foreach (glob("{$this->path}/sess_*") as $file) {
            if (filemtime($file) + $max_lifetime < time()) {
                if (unlink($file)) {
                    $count++;
                }
            }
        }

        return $count;
    }
}
