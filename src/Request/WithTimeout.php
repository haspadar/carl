<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

final readonly class WithTimeout implements Request
{
    public function __construct(
        private Request $origin,
        private int $seconds
    ) {
    }

    #[Override]
    public function options(): array
    {
        return $this->origin->options() + [
                CURLOPT_TIMEOUT => $this->seconds,
            ];
    }
}
