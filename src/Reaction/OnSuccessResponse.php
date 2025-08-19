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
 * - The callback receives only the {@see Response}, ignoring the {@see Request}.
 * - Ignores failures: {@see onFailure()} is intentionally left empty.
 *
 * Useful for cases where you only need the response body/headers
 * and don’t care about request context or failures.
 */
final readonly class OnSuccessResponse implements Reaction
{
    /**
     * @param Closure(Response):void $ok
     *        Callback invoked for every successful response.
     */
    public function __construct(private Closure $ok)
    {
    }

    #[Override]
    public function onSuccess(Request $request, Response $response): void
    {
        ($this->ok)($response);
    }

    #[Override]
    public function onFailure(Request $request, string $error): void
    {
        // no-op: failures are intentionally ignored
    }
}
