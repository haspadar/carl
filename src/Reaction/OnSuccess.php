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
 * Reaction that triggers a callback only on successful responses.
 *
 * - Executes the provided Closure when {@see onSuccess()} is called.
 * - Ignores failures: {@see onFailure()} is intentionally left empty.
 *
 * Useful for attaching side-effects (logging, assertions, counters, etc.)
 * that should run only when the request succeeded.
 */
final readonly class OnSuccess implements Reaction
{
    /**
     * @param Closure(Request, Response):void $ok
     *        Callback invoked for every successful response.
     */
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
        // no-op: failures are intentionally ignored
    }
}
