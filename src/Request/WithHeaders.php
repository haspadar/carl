<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds a list of HTTP headers to the request.
 *
 * Appends each given header string to the existing list
 * of `CURLOPT_HTTPHEADER`, if any. Does not check for duplicates.
 *
 * Useful for attaching multiple headers at once.
 *
 * Decorates another {@see Request}.
 */
final readonly class WithHeaders implements Request
{
    /**
     * @param list<string> $headers
     */
    public function __construct(private Request $origin, private array $headers)
    {
    }

    #[Override]
    public function options(): array
    {
        $options = $this->origin->options();

        /** @var list<string> $existing */
        $existing = [];
        if (isset($options[CURLOPT_HTTPHEADER]) && is_array($options[CURLOPT_HTTPHEADER])) {
            foreach ($options[CURLOPT_HTTPHEADER] as $header) {
                if (is_string($header)) {
                    $existing[] = $header;
                }
            }
        }

        $options[CURLOPT_HTTPHEADER] = array_merge($existing, $this->headers);
        return $options;
    }
}
