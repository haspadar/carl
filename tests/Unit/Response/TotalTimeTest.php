<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\TotalTime;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TotalTimeTest extends TestCase
{
    #[Test]
    public function returnsTotalTime(): void
    {
        $response = new TotalTime(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo(['total_time' => 1.234]),
            ),
        );

        $this->assertEqualsWithDelta(
            1.234,
            $response->seconds(),
            1e-12,
            'TotalTime must return the total_time value from CurlInfo',
        );
    }

    #[Test]
    public function returnsZeroIfMissing(): void
    {
        $response = new TotalTime(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo([]),
            ),
        );

        $this->assertEqualsWithDelta(
            0.0,
            $response->seconds(),
            1e-12,
            'TotalTime must return ~0.0 when total_time is missing',
        );
    }
}
