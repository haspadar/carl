<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds the `CURLOPT_COOKIE` option with a cookie string.
 *
 * Useful for passing session or custom cookies in a single header-like string
 * (e.g., "name=value; other=123").
 *
 * Decorates another {@see Request}.
 */
final readonly class WithCookies implements Request
{
    public function __construct(
        private Request $origin,
        private string $cookie
    ) {
    }

    #[Override]
    public function options(): array
    {
        return array_replace(
            $this->origin->options(),
            [CURLOPT_COOKIE => $this->cookie]
        );
    }
}
