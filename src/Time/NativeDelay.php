<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Time;

use Override;

final readonly class NativeDelay implements Delay
{
    #[Override]
    public function sleep(int $microseconds): void
    {
        usleep($microseconds);
    }
}
