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
 * HTTP version override decorator.
 *
 * @param int $version One of:
 *  - CURL_HTTP_VERSION_NONE
 *  - CURL_HTTP_VERSION_1_0
 *  - CURL_HTTP_VERSION_1_1
 *  - CURL_HTTP_VERSION_2_0
 *  - CURL_HTTP_VERSION_2TLS
 *  - CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE
 *  - CURL_HTTP_VERSION_3
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
        /** @var list<int> $allowed */
        $allowed = [
            CURL_HTTP_VERSION_NONE,
            CURL_HTTP_VERSION_1_0,
            CURL_HTTP_VERSION_1_1,
        ];

        foreach (
            [
                'CURL_HTTP_VERSION_2_0',
                'CURL_HTTP_VERSION_2TLS',
                'CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE',
                'CURL_HTTP_VERSION_3',
            ] as $name
        ) {
            if (defined($name)) {
                /** @var int $value */
                $value = constant($name);
                $allowed[] = $value;
            }
        }

        if (!in_array($this->version, $allowed, true)) {
            throw new InvalidArgumentException('Unsupported HTTP version: ' . $this->version);
        }

        $options = $this->origin->options();
        $options[CURLOPT_HTTP_VERSION] = $this->version;
        return $options;
    }
}
