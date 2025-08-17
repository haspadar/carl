<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Reaction;

use Carl\Request\Request;
use Carl\Response\Response;
use Closure;
use Override;

final readonly class OnSuccess implements Reaction
{
    public function __construct(private Closure $ok)
    {
    }

    #[Override]
    public function onSuccess(Request $request, Response $response): void
    {
        ($this->ok)($request, $response);
    }

    #[Override]
    public function onFailure(Request $request, string $error): void
    {
    }
}
