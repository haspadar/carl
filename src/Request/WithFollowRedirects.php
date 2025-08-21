<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds automatic following of redirects to the request.
 *
 * Sets `CURLOPT_FOLLOWLOCATION` and optionally limits the maximum number of redirects
 * using `CURLOPT_MAXREDIRS` (default: 10).
 *
 * Notes:
 * - cURL may switch POST/PUT to GET on 301/302/303 by default. If you need to preserve
 *   the original method on 301/302, also set `CURLOPT_POSTREDIR` accordingly.
 * - In environments with `open_basedir`, PHP can ignore `CURLOPT_FOLLOWLOCATION`.
 */
final readonly class WithFollowRedirects implements Request
{
    public function __construct(
        private Request $origin,
        private int $max = 10
    ) {
    }

    #[Override]
    public function options(): array
    {
        return array_replace(
            $this->origin->options(),
            [
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => $this->max,
            ]
        );
    }
}
