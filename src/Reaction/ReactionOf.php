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

/**
 * Generic {@see Reaction} based on two closures.
 *
 * Both success and failure behavior are provided at construction:
 * - {@see onSuccess()} executes the given $onSuccess callback
 *   with {@see Request} and {@see Response};
 * - {@see onFailure()} executes the given $onFailure callback
 *   with {@see Request} and error message.
 *
 * Useful for quick inline reactions without creating
 * dedicated classes like {@see OnSuccess} or {@see OnFailure}.
 */
final readonly class ReactionOf implements Reaction
{
    public function __construct(private Closure $onSuccess, private Closure $onFailure)
    {
    }

    #[Override]
    public function onSuccess(Request $request, Response $response): void
    {
        ($this->onSuccess)($request, $response);
    }

    #[Override]
    public function onFailure(Request $request, string $error): void
    {
        ($this->onFailure)($request, $error);
    }
}
