<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds an `Accept: application/json` header to the request.
 *
 * If an Accept header is already present, it will not be added again.
 *
 * Decorates another {@see Request}.
 *
 * Example:
 * new WithJsonAccept($request);
 */
final readonly class WithJsonAccept implements Request
{
    public function __construct(private Request $origin)
    {
    }

    #[Override]
    public function options(): array
    {
        return new WithHeaderOnce($this->origin, 'Accept', 'application/json')->options();
    }
}
