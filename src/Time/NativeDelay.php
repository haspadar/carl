<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Time;

use Override;

/**
 * Default implementation of {@see Delay} using native usleep().
 *
 * This class introduces a real delay (blocking), typically used in production.
 * For testing or non-blocking scenarios, use a fake or no-op implementation.
 *
 * @codeCoverageIgnore
 */
final readonly class NativeDelay implements Delay
{
    #[Override]
    public function sleep(int $microseconds): void
    {
        usleep($microseconds);
    }
}
