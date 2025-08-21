<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Decorator that adds a specific cURL option to a request.
 *
 * This class allows setting an arbitrary cURL option without needing
 * a dedicated wrapper class like WithTimeout or WithFollowRedirects.
 *
 * Example:
 * new WithCurlOption($request, CURLOPT_TIMEOUT, 3)
 */
final readonly class WithCurlOption implements Request
{
    public function __construct(
        private Request $origin,
        private int $option,
        private mixed $value,
    ) {
    }

    #[Override]
    public function options(): array
    {
        return $this->origin->options() + [
                $this->option => $this->value,
            ];
    }
}
