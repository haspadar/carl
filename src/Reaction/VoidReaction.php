<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Reaction;

use Carl\Request\Request;
use Carl\Response\Response;
use Override;

/**
 * {@see Reaction} that ignores both success and failure.
 *
 * Acts as a "no-op" (null object) when you don't want
 * to attach any behavior but still need a {@see Reaction}
 * implementation.
 *
 * @codeCoverageIgnore
 */
final readonly class VoidReaction implements Reaction
{
    #[Override]
    public function onSuccess(Request $request, Response $response): void
    {
        // intentionally left blank
    }

    #[Override]
    public function onFailure(Request $request, string $error): void
    {
        // intentionally left blank
    }
}
