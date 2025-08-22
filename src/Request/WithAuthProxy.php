<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds proxy authentication to a cURL request.
 *
 * Sets `CURLOPT_PROXY` to the proxy URL and `CURLOPT_PROXYUSERPWD` to
 * "username:password" for HTTP/S proxy authentication.
 *
 * Override semantics: replaces any existing `CURLOPT_PROXY`/`CURLOPT_PROXYUSERPWD`
 * values from the origin via `array_replace()`. If you need to control the proxy
 * auth method explicitly, set `CURLOPT_PROXYAUTH` in an additional decorator.
 * Be mindful not to log request options, as they contain credentials in plain text.
 *
 * Decorates another {@see Request}.
 */
final readonly class WithAuthProxy implements Request
{
    public function __construct(
        private Request $origin,
        private string $proxyUrl,
        private string $username,
        private string $password
    ) {
    }

    #[Override]
    public function options(): array
    {
        return array_replace(
            $this->origin->options(),
            [
                CURLOPT_PROXY => $this->proxyUrl,
                CURLOPT_PROXYUSERPWD => "$this->username:$this->password",
            ]
        );
    }
}
