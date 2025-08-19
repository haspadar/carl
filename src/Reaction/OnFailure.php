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
 * Reaction that triggers a callback only on failed requests.
 *
 * - Executes the provided Closure when {@see onFailure()} is called.
 * - Ignores successful responses: {@see onSuccess()} is intentionally left empty.
 *
 * Useful for attaching side-effects (logging, retries, alerts, etc.)
 * that should run only when the request failed.
 */
final readonly class OnFailure implements Reaction
{
    /**
     * @param Closure(Request, string):void $fail
     *        Callback invoked for every failure with the request and error message.
     */
    public function __construct(private Closure $fail)
    {
    }

    #[Override]
    public function onSuccess(Request $request, Response $response): void
    {
        // no-op: successes are intentionally ignored
    }

    #[Override]
    public function onFailure(Request $request, string $error): void
    {
        ($this->fail)($request, $error);
    }
}
