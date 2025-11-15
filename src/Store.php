<?php

namespace Codemonster\Session;

use SessionHandlerInterface;

class Store
{
    protected string $id;
    protected array $data = [];
    protected SessionHandlerInterface $handler;

    public function __construct(SessionHandlerInterface $handler, ?string $id = null)
    {
        $existing = $_COOKIE['SESSION_ID'] ?? null;

        $this->id = $id ?? $existing ?? bin2hex(random_bytes(16));

        $this->handler = $handler;
    }

    public function start(): void
    {
        if (!isset($_COOKIE['SESSION_ID']) || $_COOKIE['SESSION_ID'] !== $this->id) {
            setcookie('SESSION_ID', $this->id, [
                'expires' => time() + 86400 * 30,
                'path' => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        $raw = $this->handler->read($this->id);

        $this->data = is_string($raw) && $raw !== '' ? (json_decode($raw, true) ?? []) : [];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function put(string $key, mixed $value): void
    {
        $this->data[$key] = $value;

        $this->persist();
    }

    public function forget(string $key): void
    {
        unset($this->data[$key]);

        $this->persist();
    }

    public function all(): array
    {
        return $this->data;
    }

    public function destroy(): void
    {
        $this->data = [];
        $this->handler->destroy($this->id);
    }

    protected function persist(): void
    {
        $this->handler->write(
            $this->id,
            json_encode($this->data, JSON_UNESCAPED_UNICODE)
        );
    }
}
