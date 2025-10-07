<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response\Fake;

use Carl\Response\CurlInfo;
use Carl\Response\Response;
use Override;

/**
 * @codeCoverageIgnore
 *
 * Decorator that overrides specific keys in CurlInfo.
 * Useful in tests to simulate different response metadata.
 */
final readonly class WithInfoOverride implements Response
{
    public function __construct(
        private Response $origin,
        /** @var array<string,mixed> */
        private array $overrides
    ) {
    }

    #[Override]
    public function body(): string
    {
        return $this->origin->body();
    }

    #[Override]
    public function headers(): array
    {
        return $this->origin->headers();
    }

    #[Override]
    public function info(): CurlInfo
    {
        return new CurlInfo(
            array_merge(
                $this->origin->info()->all(),
                $this->overrides
            )
        );
    }
}
