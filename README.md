# 🧊 Carl

[![PHP Version](https://img.shields.io/badge/PHP-8.4-blue)](https://www.php.net/releases/8.4/)
[![cURL](https://img.shields.io/badge/ext--curl-required-brightgreen)](https://www.php.net/manual/en/book.curl.php)
[![Code Style](https://img.shields.io/badge/Code%20Style-PSR--12-blue)](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
[![CI](https://github.com/haspadar/carl/actions/workflows/ci.yml/badge.svg)](https://github.com/haspadar/carl/actions/workflows/ci.yml)
[![PHP Metrics](https://img.shields.io/badge/Metrics-phpmetrics%203.0-blue)](https://phpmetrics.org/)

[![Tests](https://img.shields.io/badge/Tests-Passing-brightgreen)](https://github.com/haspadar/carl/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/haspadar/carl/coverage.svg?branch=main&t=1)](https://app.codecov.io/gh/haspadar/carl)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%209-brightgreen)](https://phpstan.org/)
[![Psalm](https://img.shields.io/badge/psalm-level%208-brightgreen)](https://psalm.dev)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fhaspadar%2Fcarl%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/haspadar/carl/main)
[![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/haspadar/carl?utm_source=oss&utm_medium=github&utm_campaign=haspadar%2Fcarl&labelColor=171717&color=FF570A&label=CodeRabbit+Reviews)](https://coderabbit.ai)

---

## 🧠 Philosophy

- ❌ No `null`, `static`, or shared state in the public API
- ✅ One object = one responsibility
- ✅ Final classes, immutability by default
- ✅ Composition over inheritance
- ✅ Lazy evaluation — heavy work is deferred until you explicitly call `outcome()` / `body()`
- ✅ Behavior and data live together
- ✅ Following SOLID principles where applicable

Inspired by [Elegant Objects](https://www.yegor256.com/elegant-objects.html)
and [cactoos](https://github.com/yegor256/cactoos).

---

## 🔄 Comparison with Guzzle

Carl and Guzzle solve the same problem — sending HTTP requests in PHP.  
But their design philosophies differ.

Although **Guzzle** has long been considered the de facto HTTP client in PHP, it has notable drawbacks:

- relies on associative arrays for configuration, which makes code less readable and harder to check statically;
- its middleware system is flexible, but hard to trace and debug;
- immutability and strict object guarantees are not enforced, which can lead to hidden side effects.

Carl takes the opposite approach: small, final, immutable objects, strict SRP, and composition via decorators.

- Guzzle is utility-first: big objects and functions to get the job done fast.
- Carl is object-first: each object does one thing, like LEGO bricks you compose together.

| Feature       | Guzzle                                | Carl                                                 |
|---------------|---------------------------------------|------------------------------------------------------|
| Style         | Procedural helpers + middleware stack | Pure OOP, small immutable objects                    |
| Client        | One monolithic Client                 | Many small classes, each with one role               |
| Extending     | Middleware stack, config arrays       | Composition + decorators                             |
| Testability   | Mocks, adapters, prophecy             | Built-in fakes for clients & responses               |
| Dependencies  | Heavy (PSR-7, PSR-18, Symfony, etc.)  | Zero deps (only PHP + cURL)                          |
| Configuration | One big array of options              | Composed decorators (`WithUserAgent`, `WithHeaders`) |
| Lazy eval     | No (eager on send)                    | Yes (`outcome()`, `body()`, etc.)                    |

Carl aligns more with Clean Code principles, while Guzzle is more of a pragmatic toolbox.

## SOLID Principles in Carl

- **SRP (Single Responsibility Principle):** Each class has one reason to change. Decorators like `WithHeaders`,
  `WithUserAgent`, `WithTimeout` do one thing; `CurlClient` handles transport; `Outcome` objects encapsulate result
  handling.
- **OCP (Open/Closed Principle):** Behavior is extended via composition and decorators without modifying existing
  classes. Add new `Request`/`Response` decorators, `Client` wrappers (e.g., `ChunkedClient`, `ThrottledClient`), or
  `Reaction` implementations.
- **LSP (Liskov Substitution Principle):** Implementations are replaceable through small, stable interfaces (`Request`,
  `Response`, `Client`, `Outcome`, `Reaction`). Fakes and real objects are interchangeable.
- **ISP (Interface Segregation Principle):** Interfaces are minimal and focused; there is no “god” interface. High-level
  code depends only on the methods it uses.
- **DIP (Dependency Inversion Principle):** High-level code depends on abstractions, not concretions. Production uses
  `CurlClient`, tests use `FakeClient`—both behind the `Client` interface.

## ⚠️ Current Approach & Plans

**Current approach:**

- Traits (e.g., `AssertsReflectedResponse`, `WithRunningServer`) are used in tests for quick composition of assertions and setup logic.
- Procedural fragments and scalars remain in the codebase, requiring `@var`/`@return` annotations for static analysis tools.

**Plans:**

- Replace procedural fragments with consistent object composition.
- Introduce value objects to reduce primitive obsession and improve clarity.
- Replace trait-based assertions with [`haspadar/gradus`](https://github.com/haspadar/gradus).
- Integrate into [`haspadar/mono`](https://github.com/haspadar/mono) to unify shared abstractions across projects.

---

## 🧪 Quality & CI

Every push and pull request is checked via GitHub Actions:

- ✅ Static analysis with [PHPStan](https://phpstan.org/) (level 9) and [Psalm](https://psalm.dev/) (level 8)
- ✅ Type coverage report via [Shepherd](https://shepherd.dev/)
- ✅ Code style check with [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) (only changed files)
- ✅ Unit tests with [PHPUnit](https://phpunit.de)
- ✅ Code coverage via [Codecov](https://codecov.io/)
- ✅ Mutation testing with [Infection](https://infection.github.io)
- ✅ Composer validation, platform checks, security audit
- ✅ Automatic refactoring via [Rector](https://github.com/rectorphp/rector)

---

## 🧩 Request Decorators

### 🔑 Common

```php
new WithHeaders($origin, ['Authorization: Bearer TOKEN'])
new WithHeader($origin, 'Accept', 'application/json')

new WithContentType($origin, 'application/xml')
new WithJsonContentType($origin)
new WithJsonAccept($origin)

new WithUserAgent($origin, 'Carl/1.0')
new WithReferer($origin, 'https://example.com')
```

### ⏱ Technical

```php
new WithTimeoutMs($origin, 30_000) // 30 seconds
new WithSslVerificationOff($origin)
new WithHttpVersion($origin, CURL_HTTP_VERSION_2_0)
new WithProxy($origin, 'http://proxy.local:8080')
```

### 🧰 Utility

```php
new WithBody($origin, 'name=John&age=30')
new WithFormBody($origin, ['name' => 'John', 'age' => 30])
new WithJsonBody($origin, ['id' => 123, 'name' => 'Alice'])
new WithCookies($origin, 'sessionid=abc123; theme=dark')
new WithFollowRedirects($origin, 5)
new WithDefaultUserAgent($origin)
```

Combine decorators via composition, for example:

```php
$request = new WithFollowRedirects(
    new WithHeaders(
        new GetRequest('https://example.com'),
        ['Accept: application/json']
    ),
    max: 5
);
```

## 📦 Built-in Requests

Carl ships with basic HTTP request objects:

```php
new GetRequest('https://api.example.com/data')
new PostRequest('https://api.example.com/submit')
new PutRequest('https://api.example.com/update/123')
new PatchRequest('https://api.example.com/modify/123')
new DeleteRequest('https://api.example.com/delete/123')
```

These objects only define the HTTP method and URL.
To send payloads, combine them with decorators:
```php
new WithJsonBody(
    new WithJsonContentType(
        new PostRequest('https://api.example.com/submit')
    ),
    ['name' => 'John', 'age' => 30]
)
```

Or use WithFormBody for application/x-www-form-urlencoded:
```php
new WithFormBody(
    new PostRequest('https://api.example.com/login'),
    ['username' => 'admin', 'password' => 'secret']
)
// Note: To enforce the content type, wrap with:
// new WithContentType($origin, 'application/x-www-form-urlencoded')
```

## 🧩 Response Decorators

Decorators that extend response behavior with additional methods or modifications.

```php
new JsonResponse($response)
new EffectiveUrl($response)
new PrimaryIp($response)
new WithStatusCode($response, 404)
new WithContentType($response, 'application/json')
```

## 🎯 Outcomes

An outcome always exists, even if the request fails — in that case it represents an error. Outcomes represent the result
of executing a request and allow reacting to
success or failure.

```php
$client = new CurlClient();
$request = new WithJsonAccept(
    new GetRequest('https://httpbin.org/get')
);

$client->outcome($request)->react(
    new OnSuccessResponse(
        fn (Response $response) => echo $response->body()
    )
);

// Example with error handling
$client->outcome(new GetRequest('https://invalid.local'))->react(
    new OnFailure(
        fn (string $error) => echo "Request failed: " . $error
    )
);
```

You can also define your own custom reactions by implementing the `Reaction` interface:

```php
final class LogResponse implements Reaction
{
    public function __construct(private string $prefix) {}
    public function onSuccess(Request $request, Response $response): void
    {
        echo $this->prefix . " success: " . $response->body();
    }
    public function onFailure(Request $request, string $error): void
    {
        echo $this->prefix . " failure: " . $error;
    }
}

$client = new CurlClient();
$request = new GetRequest('https://httpbin.org/get');

$client->outcome($request)->react(
    new LogResponse('[Carl]')
);
```

### Outcome decorators

Besides working directly with `CurlClient`, you can wrap it with additional **decorators** to control request execution
strategy.  
These decorators also work with `outcome()` and `outcomes()` methods:

- `ChunkedClient` — splits a batch of requests into smaller chunks (useful for APIs with batch limits).
- `ThrottledClient` — adds a delay between requests or chunks (to avoid hitting rate limits).

```php
$client = new ChunkedClient(
    new ThrottledClient(new CurlClient(), 0.5), // 0.5s delay
    10 // chunk size
);

$outcomes = $client->outcomes($requests, new MyReaction());
```

---

## 🧪 Testing with Fakes

Carl provides a set of fake classes for convenient, isolated unit testing without making real HTTP requests. You can
also swap the real client with `FakeClient` to drive predefined outcomes.

**Fake Outcomes** (in `Carl\Outcome\Fake`):

- `AlwaysSuccessful` — returns an always-successful outcome (HTTP 200)
- `AlwaysFails` — returns an always-failed outcome with a given error
- `Cycle` — cycles through a list of outcomes in order
- `FakeStatus` — returns an outcome with HTTP status code derived from the URI path

```php
$response = new NotFoundResponse();
$this->assertSame(404, $response->info()->value(CURLINFO_RESPONSE_CODE)); 
``` 

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
        fn (Response $response) => echo $response->body()
    )
);
// Sequence: OK, error, OK, error, ...
```

**Fake Responses** (in `Carl\Response\Fake`):

| Class                | HTTP Code | Use case                 |
|----------------------|-----------|--------------------------|
| SuccessResponse      | 200       | Successful requests      |
| RedirectResponse     | 302       | Redirection scenarios    |
| ClientErrorResponse  | 400       | Invalid input simulation |
| UnauthorizedResponse | 401       | Auth errors              |
| NotFoundResponse     | 404       | Missing resources        |
| ServerErrorResponse  | 500       | Simulated server failure |

These fakes allow you to simulate different scenarios easily in your tests.

```php
$outcome = new AlwaysSuccessful(
    new SuccessResponse("Hello, World!")
);
```

This way you can test how your code reacts to success, failure, or specific HTTP status codes without relying on real
network calls.

---

## 💤 Lazy Evaluation

Carl objects never perform heavy work in constructors.
Objects are lightweight to create, and heavy operations (network I/O, parsing, reacting) are deferred until you
explicitly call:

- `outcome()` / `outcomes()` — executes the request(s) and produces outcomes
- `body()` — reads and parses the response body
- reaction handlers like `OnSuccessResponse` or `OnFailure`

This ensures:

- predictable and testable behavior (nothing happens “magically” on instantiation)
- fast object composition
- testability — objects remain lightweight until you actually need results

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
