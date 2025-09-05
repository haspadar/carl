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

    public function seconds(): float
    {
        $info = $this->origin->info();

        if ($info->hasKey('total_time')) {
            return (float)$info->value('total_time', '0');
        }

        if ($info->hasKey('total_time_us')) {
            return (float)$info->value('total_time_us', '0') / 1_000_000.0;
        }

        return 0.0;
    }
}
