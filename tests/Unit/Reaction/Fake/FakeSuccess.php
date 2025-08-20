<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Reaction\Fake;

use Carl\Reaction\Reaction;
use Carl\Request\Request;
use Carl\Response\Response;
use Override;

final class FakeSuccess implements Reaction
{
    private int $count = 0;

    #[Override]
    public function onSuccess(Request $request, Response $response): void
    {
        $this->count++;
    }

    #[Override]
    public function onFailure(Request $request, string $error): void
    {
        // nothing
    }

    public function total(): int
    {
        return $this->count;
    }
}
