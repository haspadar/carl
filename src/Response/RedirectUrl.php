<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

use Override;

/**
 * Decorator that exposes the last redirect URL from CurlInfo.
 *
 * Example:
 * $response = new RedirectUrl($origin);
 * echo $response->url(); // e.g., "https://example.com/final"
 */
final readonly class RedirectUrl implements Response
{
    public function __construct(private Response $origin)
    {
    }

    #[Override]
    /** @codeCoverageIgnore */
    public function body(): string
    {
        return $this->origin->body();
    }

    #[Override]
    /** @codeCoverageIgnore */
    public function headers(): array
    {
        return $this->origin->headers();
    }

    #[Override]
    /** @codeCoverageIgnore */
    public function info(): CurlInfo
    {
        return $this->origin->info();
    }

    public function value(): string
    {
        return $this->origin->info()->value('redirect_url');
    }
}
