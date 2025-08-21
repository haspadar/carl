<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Includes response headers in the raw response body.
 *
 * Sets `CURLOPT_HEADER` to `true`, so that server response
 * headers are prepended to the body returned by cURL.
 *
 * Useful when you want to inspect or parse HTTP headers
 * manually from the raw response.
 *
 * Decorates another {@see Request}.
 */
final readonly class WithHeaderIncluded implements Request
{
    public function __construct(private Request $origin)
    {
    }

    #[Override]
    public function options(): array
    {
        return $this->origin->options() + [
                CURLOPT_HEADER => true,
            ];
    }
}
