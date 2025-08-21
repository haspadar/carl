<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds a raw string body to the request.
 *
 * Sets `CURLOPT_POSTFIELDS` with the provided payload. Combine with
 * content-type decorators such as {@see WithContentType} or {@see WithJsonContentType}.
 *
 * Decorates another {@see Request}.
 */
final readonly class WithBody implements Request
{
    public function __construct(
        private Request $origin,
        private string $body
    ) {
    }

    #[Override]
    public function options(): array
    {
        $options = $this->origin->options();
        $options[CURLOPT_POSTFIELDS] = $this->body;
        return $options;
    }
}
