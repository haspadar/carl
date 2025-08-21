<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds a timeout to the request in **milliseconds**.
 *
 * Sets the `CURLOPT_TIMEOUT_MS` option to limit the maximum execution time.
 * If the request takes longer than the specified time, it will be aborted by cURL.
 *
 * ⚠️ Requires cURL 7.16.2+.
 *
 * Example:
 * new WithTimeoutMs($request, 1500); // aborts if request exceeds 1.5 seconds
 *
 * @see https://www.php.net/manual/en/function.curl-setopt.php
 */
final readonly class WithTimeoutMs implements Request
{
    public function __construct(
        private Request $origin,
        private int $microseconds,
    ) {
    }

    #[Override]
    public function options(): array
    {
        return array_replace(
            $this->origin->options(),
            [CURLOPT_TIMEOUT_MS => $this->microseconds]
        );
    }
}
