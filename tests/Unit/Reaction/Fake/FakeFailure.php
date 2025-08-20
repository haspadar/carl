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

final class FakeFailure implements Reaction
{
    private int $total = 0;

    #[Override]
    public function onSuccess(Request $request, Response $response): void
    {
        // nothing
    }

    #[Override]
    public function onFailure(Request $request, string $error): void
    {
        $this->total++;
    }

    public function total(): int
    {
        return $this->total;
    }
}
