# 🧊 Carl

[![PHP Version](https://img.shields.io/badge/PHP-8.4-blue)](https://www.php.net/releases/8.4/)
[![CI](https://github.com/haspadar/carl/actions/workflows/ci.yml/badge.svg)](https://github.com/haspadar/carl/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/haspadar/carl/coverage.svg?branch=main)](https://app.codecov.io/gh/haspadar/carl)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%209-brightgreen)](https://phpstan.org/)
[![Psalm](https://img.shields.io/badge/psalm-level%208-brightgreen)](https://psalm.dev)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fhaspadar%2Fcarl%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/haspadar/carl/main)
[![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/haspadar/carl?utm_source=oss&utm_medium=github&utm_campaign=haspadar%2Fcarl&labelColor=171717&color=FF570A&label=CodeRabbit+Reviews)](https://coderabbit.ai)

Immutable HTTP client for PHP built on cURL. Pure OOP, no `null`, no `static`.  
Inspired by [Elegant Objects](https://www.elegantobjects.org/#principles) and [cactoos](https://github.com/yegor256/cactoos).

---

## Features

- Final, immutable classes with single responsibility  
- No `null` values anywhere  
- No `static` methods or state  
- Lazy evaluation of HTTP requests  
- Built-in fake clients and responses for testing  
- No external dependencies except PHP and cURL  

---

## Simple Request Example

```php
$response = new CurlClient()->outcome(
    new GetRequest('https://httpbin.org/get')
)->response();

echo $response->body(); 
```

---

## Parallel Requests with onSuccess and onFailure

```php
$client = new CurlClient();

$requests = [
    new GetRequest('https://httpbin.org/status/200'),
    new GetRequest('https://httpbin.org/status/404'),
];

$outcomes = $client->outcomes($requests, new class implements Reaction {
    public function onSuccess(Request $request, Response $response): void
    {
        echo "Success: " . $response->body() . "\n";
    }
    public function onFailure(Request $request, string $error): void
    {
        echo "Failure: " . $error . "\n";
    }
});
+Note: CurlClient returns outcomes in completion order (not request order).

```

---

## 🧪 Testing with Fakes

Carl provides fake classes for isolated unit testing without real HTTP calls. You can replace the real client with `FakeClient` to drive predefined outcomes.

Examples of fake outcomes:

- `AlwaysSuccessful` — always returns success (HTTP 200)
- `AlwaysFails` — always returns a failure with a given error message
- `Cycle` — cycles through a list of outcomes in order
- `FakeStatus` — returns an outcome with HTTP status code derived from the URI path

Example usage:

```php
new FakeClient(new Cycle([
    new AlwaysSuccessful(new SuccessResponse("OK")),
    new AlwaysFails("network error"),
]))->outcomes(
    [
        new GetRequest('https://example.com/a'),
        new GetRequest('https://example.com/b'),
    ], 
    new OnSuccessResponse(
        fn (Response $response) => print $response->body()
    )
);
// Sequence: OK, error, OK, error, ...
```

---

## 💤 Lazy Evaluation

Carl objects are lightweight and perform no heavy work in constructors. Network I/O occurs only when you call `outcome()` or `outcomes()`. Response parsing/consumption (e.g., `body()`) is deferred until you access it. This keeps composition predictable and fast.

---

## 📥 Installation

```bash
composer require haspadar/carl
```

```php
$response = new CurlClient()->outcome(
    new GetRequest('https://httpbin.org/get')
)->response();

echo $response->body(); 
```

### Requirements

- PHP 8.4+
- ext-curl (enabled by default in most PHP distributions)

## 📄 License

[MIT](LICENSE)
