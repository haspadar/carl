# 🧊 Carl

[![PHP Version](https://img.shields.io/badge/PHP-8.4-blue)](https://www.php.net/releases/8.4/)
[![Code Style](https://img.shields.io/badge/Code%20Style-PSR--12-blue)](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
[![CI](https://github.com/haspadar/carl/actions/workflows/ci.yml/badge.svg)](https://github.com/haspadar/carl/actions/workflows/ci.yml)
[![PHP Metrics](https://img.shields.io/badge/Metrics-phpmetrics%203.0-blue)](https://phpmetrics.org/)

[![Tests](https://img.shields.io/badge/Tests-Passing-brightgreen)](https://github.com/haspadar/carl/actions/workflows/ci.yml)
[![Coverage](https://codecov.io/gh/haspadar/carl/branch/main/graph/badge.svg)](https://codecov.io/gh/haspadar/carl)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%209-brightgreen)](https://phpstan.org/)
[![Psalm](https://img.shields.io/badge/psalm-level%208-brightgreen)](https://psalm.dev)
[![Psalm Type Coverage](https://shepherd.dev/github/haspadar/carl/coverage.svg)](https://shepherd.dev/github/haspadar/carl)
[![Mutation MSI](https://img.shields.io/badge/Mutation%20MSI-100%25-brightgreen)](https://infection.github.io/)
[![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/haspadar/carl?utm_source=oss&utm_medium=github&utm_campaign=haspadar%2Fcarl&labelColor=171717&color=FF570A&label=CodeRabbit+Reviews)](https://coderabbit.ai)

---

## 🧠 Philosophy

- ❌ No `null`, `static`, or shared state in the public API
- ✅ One object = one responsibility
- ✅ Final classes, immutability by default
- ✅ Composition over inheritance
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

If you prefer Uncle Bob’s Clean Code — Carl follows it rigorously.  
If you prefer a pragmatic toolbox — Guzzle might be enough.

## фвв

- **SRP (Single Responsibility Principle):** Each class has one reason to change. Decorators like `WithHeaders`, `WithUserAgent`, `WithTimeout` do one thing; `CurlClient` handles transport; `Outcome` encapsulates result handling.
- **OCP (Open/Closed Principle):** Behavior is extended via composition and decorators without modifying existing classes. Add new `Request`/`Response` decorators, `Client` wrappers (e.g., `ChunkedClient`, `ThrottledClient`), or `Reaction` implementations.
- **LSP (Liskov Substitution Principle):** Implementations are replaceable through small, stable interfaces (`Request`, `Response`, `Client`, `Outcome`, `Reaction`). Fakes and real objects are interchangeable.
- **ISP (Interface Segregation Principle):** Interfaces are minimal and focused; there is no “god” interface. High-level code depends only on the methods it uses.
- **DIP (Dependency Inversion Principle):** High-level code depends on abstractions, not concretions. Production uses `CurlClient`, tests use `FakeClient`—both behind the `Client` interface.

## ⚠️ Limitations & Plans

**Limitations:**

- Many procedural fragments and scalars remain in the codebase.
- This results in numerous `phpstan` ignore directives.

**Plans:**

- Gradually remove procedural code.
- Reduce scalar leakage by introducing value objects.
- Integrate into [`haspadar/mono`](https://github.com/haspadar/mono) for shared abstractions and consistency.

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

Examples of request decorators with realistic data:

```php
new WithAuth($origin, 'user123', 'secret!')
new WithAuthProxy($origin, 'http://proxy.example.com:8080', 'proxyUser', 'proxyPass')
new WithBody($origin, 'name=John&age=30', 'application/x-www-form-urlencoded')
new WithConnectionTimeout($origin, 10)
new WithCookies($origin, 'sessionid=abc123; theme=dark')
new WithEncoding($origin, '')
new WithFollowRedirects($origin, 5)
new WithHeaderIncluded($origin)
new WithHeaders($origin, ['Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9'])
new WithHttpVersion($origin, CURL_HTTP_VERSION_2_0)
new WithJsonAcceptHeader($origin)
new WithJsonBody($origin, ['name' => 'Alice', 'email' => 'alice@example.com'])
new WithProxy($origin, 'http://proxy.example.com:3128')
new WithReferer($origin, 'https://referrer.example.com/page')
new WithTimeout($origin, 30)
new WithUserAgent($origin, 'Mozilla/5.0 (compatible; CarlClient/1.0)')
new WithCurlOption($origin) 
new WithSslVerificationOff($origin) 
```

Basic requests are also available as follows:

```php
new GetRequest('https://api.example.com/data')
new PostRequest('https://api.example.com/submit', '{"name":"John","age":30}')
new PutRequest('https://api.example.com/update/123')
new PatchRequest('https://api.example.com/modify/123')
new DeleteRequest('https://api.example.com/delete/123')
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

Not every request guarantees a response. Outcomes represent the result of executing a request and allow reacting to
success or failure.

```php
$client = new CurlClient();
$request = new WithJsonAcceptHeader(
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

Carl provides a set of fake classes for convenient, isolated unit testing without making real HTTP requests. You can also swap the real client with `FakeClient` to drive predefined outcomes.

**Fake Outcomes** (in `Carl\Outcome\Fake`):

- `AlwaysSuccessful`
- `AlwaysFails`
- `Cycle`

```php
$alwaysSuccess = new AlwaysSuccessful(new SuccessResponse("OK"));
```

```php
$client = new FakeClient(new Cycle([
    new AlwaysSuccessful(new SuccessResponse("OK")),
    new AlwaysFails("network error"),
]));

$client->outcomes([$request1, $request2], new OnSuccessResponse(fn($res) => echo $res->body()));
```

**Fake Responses** (in `Carl\Response\Fake`):

- `SuccessResponse`
- `ServerErrorResponse`
- `NotFoundResponse`
- `RedirectResponse`

These fakes allow you to simulate different scenarios easily in your tests.

```php
$outcome = new AlwaysSuccessful(
    new SuccessResponse("Hello, World!")
);
```

This way you can test how your code reacts to success, failure, or specific HTTP status codes without relying on real
network calls.

---

## 📥 Installation

```bash
composer require haspadar/carl
```

Requires PHP 8.4.
---

## 📄 License

[MIT](LICENSE)
