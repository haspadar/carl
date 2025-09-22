<?php

declare(strict_types=1);

namespace Carl\Response\Fake;

use Carl\Response\CurlInfo;
use Carl\Response\Response;
use Override;

/**
 * Decorator that ensures a Response always has
 * a minimal set of default HTTP headers.
 */
final readonly class WithHeaderDefaults implements Response
{
    /** @var array<string,string> */
    private array $defaults;

    public function __construct(private Response $origin)
    {
        $this->defaults = [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Length' => (string)strlen($this->origin->body()),
            'Server' => 'FakeServer/1.0',
            'Date' => gmdate(DATE_RFC7231),
            'Connection' => 'close',
        ];
    }

    #[Override]
    public function body(): string
    {
        return $this->origin->body();
    }

    #[Override]
    public function headers(): array
    {
        $headers = $this->origin->headers();

        return array_merge($this->defaults, $headers);
    }

    #[Override]
    public function info(): CurlInfo
    {
        return $this->origin->info();
    }
}
