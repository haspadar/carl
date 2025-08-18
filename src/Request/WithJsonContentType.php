<?php

declare(strict_types=1);

namespace Carl\Request;

use Override;

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
