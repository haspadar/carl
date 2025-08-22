<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds or replaces a single HTTP header.
 *
 * Ensures the header appears only once in `CURLOPT_HTTPHEADER` by removing
 * any existing headers with the same name (case-insensitive).
 *
 * Decorates another {@see Request}.
 */
final readonly class WithHeader implements Request
{
    public function __construct(
        private Request $origin,
        private string $name,
        private string $value,
    ) {
    }

    #[Override]
    public function options(): array
    {
        $options = $this->origin->options();

        $headers = [];
        if (isset($options[CURLOPT_HTTPHEADER]) && is_array($options[CURLOPT_HTTPHEADER])) {
            foreach ($options[CURLOPT_HTTPHEADER] as $header) {
                if (!is_string($header)) {
                    continue;
                }
                if (str_contains($header, "\r")) {
                    continue;
                }
                if (str_contains($header, "\n")) {
                    continue;
                }
                $pos = strpos($header, ':');
                if ($pos === false) {
                    continue;
                }
                $existingName = substr($header, 0, $pos);
                if (strcasecmp($existingName, $this->name) !== 0) {
                    $headers[] = $header;
                }
            }
        }

        $headers[] = $this->name . ': ' . $this->value;
        $options[CURLOPT_HTTPHEADER] = $headers;

        return $options;
    }
}
