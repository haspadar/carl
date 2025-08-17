// review: noop

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

final readonly class VoidReaction implements Reaction
{
    #[Override]
    public function onSuccess(Request $request, Response $response): void
    {
        // nothing
    }

    #[Override]
    public function onFailure(Request $request, string $error): void
    {
        // nothing
    }
}
