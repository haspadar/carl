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
    public function returnsTotalTimeWhenPresent(): void
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
            'Must return total_time value when present',
        );
    }

    #[Test]
    public function convertsMicrosecondsToSecondsWhenOnlyTotalTimeUsExists(): void
    {
        $response = new TotalTime(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo(['total_time_us' => 1_234_000]),
            ),
        );

        $this->assertEqualsWithDelta(
            1.234,
            $response->seconds(),
            1e-12,
            'Must convert microseconds to seconds when only total_time_us present',
        );
    }

    #[Test]
    public function returnsZeroWhenNoTimingKeysExist(): void
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
            'Must return 0.0 when no total_time or total_time_us keys exist',
        );
    }

    #[Test]
    public function proxiesBodyFromOrigin(): void
    {
        $response = new TotalTime(
            new CurlResponse('body-value', [], new CurlInfo([])),
        );

        $this->assertSame(
            'body-value',
            $response->body(),
            'Must proxy body() from origin response',
        );
    }

    #[Test]
    public function proxiesHeadersFromOrigin(): void
    {
        $response = new TotalTime(
            new CurlResponse(
                'irrelevant',
                ['X-Test' => 'yes'],
                new CurlInfo([]),
            ),
        );

        $this->assertSame(
            ['X-Test' => 'yes'],
            $response->headers(),
            'Must proxy headers() from origin response',
        );
    }

    #[Test]
    public function proxiesInfoFromOrigin(): void
    {
        $info = new CurlInfo(['http_code' => 200]);
        $response = new TotalTime(
            new CurlResponse('irrelevant', [], $info),
        );

        $this->assertSame(
            $info,
            $response->info(),
            'Must proxy info() from origin response',
        );
    }
}
