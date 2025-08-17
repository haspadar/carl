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
        $headers = array_key_exists(CURLOPT_HTTPHEADER, $options)
            ? $options[CURLOPT_HTTPHEADER]
            : [];
        $headers[] = 'Accept: application/json';

        return $options + [
            CURLOPT_HTTPHEADER => $headers,
        ];
    }
}
