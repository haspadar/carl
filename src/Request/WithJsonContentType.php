<?php

declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds a `Content-Type: application/json` header to the request.
 *
 * Useful when sending JSON payloads to ensure the server
 * correctly interprets the request body.
 *
 * Often used together with {@see WithJsonBody}.
 *
 * Decorates another {@see Request}.
 *
 * Example:
 * new WithJsonContentType($request);
 */
final readonly class WithJsonContentType implements Request
{
    public function __construct(private Request $origin)
    {
    }

    /** @return array<int|string, mixed> */
    #[Override]
    public function options(): array
    {
        return new WithContentType(
            $this->origin,
            'application/json',
        )->options();
    }
}
