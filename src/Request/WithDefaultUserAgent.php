<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Carl\CurlVersionOf;
use Override;

/**
 * Adds a default `User-Agent` header to the request if not already set.
 *
 * The value is generated based on the PHP version and cURL version, e.g.:
 *     "Carl/0.1 PHP/8.4.0 curl/8.7.1"
 *
 * This is useful when sending requests without explicitly specifying
 * a user agent, ensuring consistent identification of the client.
 *
 * Decorates another {@see Request} and delegates options after appending the header.
 */
final readonly class WithDefaultUserAgent implements Request
{
    public function __construct(private Request $origin)
    {
    }

    #[Override]
    public function options(): array
    {
        /** @var array<string,mixed>|false $cv */
        $cv = curl_version();

        return new WithUserAgent(
            $this->origin,
            sprintf('Carl/0.1 PHP/%s curl/%s', PHP_VERSION, new CurlVersionOf($cv)->value())
        )->options();
    }
}
