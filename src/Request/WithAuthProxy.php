<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

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
        return $this->origin->options() + [
                CURLOPT_PROXY => $this->proxyUrl,
                CURLOPT_PROXYUSERPWD => "$this->username:$this->password",
            ];
    }
}
