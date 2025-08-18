<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

final readonly class WithReferer implements Request
{
    public function __construct(
        private Request $origin,
        private string $referer
    ) {
    }

    #[Override]
    public function options(): array
    {
        return new WithHeaderOnce($this->origin, 'Referer', $this->referer)->options();
    }
}
