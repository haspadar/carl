<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

use Override;

/**
 * Decorator that exposes the redirect count from CurlInfo.
 *
 * Example:
 * $response = new RedirectCount($origin);
 * echo $response->count(); // e.g., 2
 */
final readonly class RedirectCount implements Response
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

    public function count(): int
    {
        return (int) $this->origin->info()->value('redirect_count');
    }
}
