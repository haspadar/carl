// review: noop

<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

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
            foreach ($options[CURLOPT_HTTPHEADER] as $h) {
                if (is_scalar($h)) {
                    $existing[] = (string)$h;
                }
            }
        }

        $options[CURLOPT_HTTPHEADER] = array_merge($existing, $this->headers);
        return $options;
    }
}
