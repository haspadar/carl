<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

final readonly class WithJsonAcceptHeader implements Request
{
    public function __construct(private Request $origin)
    {
    }

    #[Override]
    public function options(): array
    {
        $options = $this->origin->options();
        /** @var list<string> $headers */
        $headers = array_key_exists(CURLOPT_HTTPHEADER, $options) && is_array($options[CURLOPT_HTTPHEADER])
            ? $options[CURLOPT_HTTPHEADER]
            : [];

        if (!in_array('Accept: application/json', $headers, true)) {
            $headers[] = 'Accept: application/json';
        }

        $options[CURLOPT_HTTPHEADER] = $headers;

        return $options;
    }
}
