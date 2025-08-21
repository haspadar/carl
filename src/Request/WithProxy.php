<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Sets a proxy server for the request via `CURLOPT_PROXY`.
 *
 * Useful when routing HTTP requests through an external proxy
 * for anonymity, geo-routing, or traffic inspection.
 *
 * Decorates another {@see Request}.
 *
 * Example:
 * new WithProxy($request, 'http://127.0.0.1:8080');
 */
final readonly class WithProxy implements Request
{
    public function __construct(
        private Request $origin,
        private string $proxyUrl
    ) {
    }

    #[Override]
    public function options(): array
    {
        return $this->origin->options() + [
                CURLOPT_PROXY => $this->proxyUrl,
            ];
    }
}
