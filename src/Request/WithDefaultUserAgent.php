// review: noop

<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Carl\CurlVersionOf;
use Override;

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
