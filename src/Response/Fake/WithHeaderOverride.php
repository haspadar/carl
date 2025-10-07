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
 * Decorator that overrides specific headers of a Response.
 * Useful in tests to simulate authentication, redirects, etc.
 */
final readonly class WithHeaderOverride implements Response
{
    public function __construct(
        private Response $origin,
        /** @var array<string,string> */
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
        return array_merge($this->origin->headers(), $this->overrides);
    }

    #[Override]
    public function info(): CurlInfo
    {
        return $this->origin->info();
    }
}
