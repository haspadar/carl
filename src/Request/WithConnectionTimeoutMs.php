<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

final readonly class WithConnectionTimeoutMs implements Request
{
    public function __construct(
        private Request $origin,
        private int $milliseconds
    ) {
    }

    #[Override]
    public function options(): array
    {
        return $this->origin->options() + [
                CURLOPT_TIMEOUT_MS => $this->milliseconds,
            ];
    }
}
