<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use InvalidArgumentException;
use Override;

/**
 * Forces a specific HTTP version for the request.
 *
 * Adds `CURLOPT_HTTP_VERSION` to cURL options.
 * Supports only the versions available in the current PHP/cURL build.
 *
 * Throws {@see InvalidArgumentException} if an unsupported version is passed.
 *
 * Decorates another {@see Request}.
 *
 * Possible values for `$version` (if defined):
 *  - `CURL_HTTP_VERSION_NONE`
 *  - `CURL_HTTP_VERSION_1_0`
 *  - `CURL_HTTP_VERSION_1_1`
 *  - `CURL_HTTP_VERSION_2_0`
 *  - `CURL_HTTP_VERSION_2TLS`
 *  - `CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE`
 *  - `CURL_HTTP_VERSION_3`
 *
 * Example:
 * new WithHttpVersion($request, CURL_HTTP_VERSION_2_0);
 */
final readonly class WithHttpVersion implements Request
{
    public function __construct(
        private Request $origin,
        private int $version,
    ) {
    }

    #[Override]
    public function options(): array
    {
        $allowed = [
            CURL_HTTP_VERSION_NONE,
            CURL_HTTP_VERSION_1_0,
            CURL_HTTP_VERSION_1_1,
        ];

        foreach ([
                     'CURL_HTTP_VERSION_2_0',
                     'CURL_HTTP_VERSION_2TLS',
                     'CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE',
                     'CURL_HTTP_VERSION_3',
                 ] as $name) {
            if (defined($name)) {
                $allowed[] = constant($name);
            }
        }

        if (!in_array($this->version, $allowed, true)) {
            throw new InvalidArgumentException("Unsupported HTTP version: {$this->version}");
        }

        return array_replace(
            $this->origin->options(),
            [CURLOPT_HTTP_VERSION => $this->version],
        );
    }
}
