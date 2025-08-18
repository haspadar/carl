<?php

declare(strict_types=1);

namespace Carl\Request;

use Override;

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
        return new WithHeaderOnce(
            $this->origin,
            'Content-Type',
            $this->mimeType,
        )->options();
    }
}
