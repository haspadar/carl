<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Fake\Time;

use Carl\Time\Delay;
use Override;

final class FakeDelay implements Delay
{
    /** @var list<int> */
    private array $calls = [];

    #[Override]
    public function sleep(int $microseconds): void
    {
        $this->calls[] = $microseconds;
    }

    /** @var list<int> */
    public function calls(): array
    {
        return $this->calls;
    }
}
