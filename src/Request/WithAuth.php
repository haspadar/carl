<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds HTTP Basic authentication to a cURL request.
 *
 * Sets `CURLOPT_HTTPAUTH` to `CURLAUTH_BASIC` and `CURLOPT_USERPWD` to
 * "username:password".
 *
 * Decorates another {@see Request}.
 */
final readonly class WithAuth implements Request
{
    public function __construct(private Request $origin, private string $user, private string $password)
    {
    }

    #[Override]
    public function options(): array
    {
        return array_replace(
            $this->origin->options(),
            [
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => "$this->user:$this->password",
            ]
        );
    }
}
