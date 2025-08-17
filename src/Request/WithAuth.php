// review: noop

<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

final readonly class WithAuth implements Request
{
    public function __construct(private Request $origin, private string $user, private string $password)
    {
    }

    #[Override]
    public function options(): array
    {
        return $this->origin->options() + [
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => "$this->user:$this->password",
            ];
    }
}
