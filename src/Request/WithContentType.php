<?php

declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds or overrides the `Content-Type` header.
 *
 * Decorates the origin request by appending a `Content-Type: <mime>` header
 * using {@see WithHeader}.
 */
final readonly class WithContentType implements Request
{
    public function __construct(
        private Request $origin,
        private string $mimeType,
    ) {
    }

    /** @return array<int|string,mixed> */
    #[Override]
    public function options(): array
    {
        return new WithHeader(
            $this->origin,
            'Content-Type',
            $this->mimeType
        )->options();
    }
}
