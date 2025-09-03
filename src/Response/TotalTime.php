<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

use Override;

/**
 * Decorator that exposes total request time in seconds.
 */
final readonly class TotalTime implements Response
{
    public function __construct(private Response $origin)
    {
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
        return $this->origin->info();
    }

    public function seconds(): float
    {
        return (float) $this->origin->info()->value('total_time');
    }
}
