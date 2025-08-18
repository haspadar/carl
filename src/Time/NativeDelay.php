<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Time;

final readonly class NativeDelay implements Delay
{
    public function sleep(int $microseconds): void
    {
        usleep($microseconds);
    }
}
