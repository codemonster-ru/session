# codemonster-ru/session

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codemonster-ru/session.svg?style=flat-square)](https://packagist.org/packages/codemonster-ru/session)
[![Total Downloads](https://img.shields.io/packagist/dt/codemonster-ru/session.svg?style=flat-square)](https://packagist.org/packages/codemonster-ru/session)
[![License](https://img.shields.io/packagist/l/codemonster-ru/session.svg?style=flat-square)](https://packagist.org/packages/codemonster-ru/session)
[![Tests](https://github.com/codemonster-ru/session/actions/workflows/tests.yml/badge.svg)](https://github.com/codemonster-ru/session/actions/workflows/tests.yml)

**Lightweight session management library for PHP** — object-oriented.

## 📦 Installation

```bash
composer require codemonster-ru/session
```

## 🚀 Usage

### Basic example

```php
use Codemonster\Session\Session;

// Start session (default: file storage)
Session::start();

// Store values
Session::put('user', 'Vasya');
Session::put('role', 'admin');

// Retrieve values
echo Session::get('user'); // Vasya

// Remove values
Session::forget('role');

// Get all session data
print_r(Session::all());

// Destroy current session
Session::destroy();
```

### Using Array handler (for tests or CLI)

```php
Session::start('array');
Session::put('debug', true);

echo Session::get('debug'); // true
```

### Using custom handler

```php
use Codemonster\Session\Session;
use App\Session\RedisSessionHandler;

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$handler = new RedisSessionHandler($redis);

Session::start(customHandler: $handler);
Session::put('user_id', 42);
```

## 🧪 Testing

You can run tests with the command:

```bash
composer test
```

## 👨‍💻 Author

[**Kirill Kolesnikov**](https://github.com/KolesnikovKirill)

## 📜 License

[MIT](https://github.com/codemonster-ru/session/blob/main/LICENSE)
