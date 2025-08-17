<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

final readonly class WithBody implements Request
{
    public function __construct(
        private Request $origin,
        private string $body,
        private string $contentType = 'application/x-www-form-urlencoded'
    ) {
    }

    #[Override]
    public function options(): array
    {
        $original = $this->origin->options();
        $headers = $original[CURLOPT_HTTPHEADER] ?? [];

        return $original + [
                CURLOPT_POSTFIELDS => $this->body,
                CURLOPT_HTTPHEADER => array_merge(
                    is_array($headers) ? $headers : [],
                    ['Content-Type: ' . $this->contentType],
                ),
            ];
    }
}
