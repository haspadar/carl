<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\RedirectCount;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RedirectCountTest extends TestCase
{
    #[Test]
    public function returnsRedirectCountWhenPresent(): void
    {
        $response = new RedirectCount(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo(['redirect_count' => 2]),
            ),
        );

        $this->assertSame(
            2,
            $response->count(),
            'Must return redirect_count value from CurlInfo when present',
        );
    }

    #[Test]
    public function returnsZeroWhenMissing(): void
    {
        $response = new RedirectCount(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo([]),
            ),
        );

        $this->assertSame(
            0,
            $response->count(),
            'Must return 0 when redirect_count key is missing',
        );
    }

    #[Test]
    public function proxiesBodyFromOrigin(): void
    {
        $response = new RedirectCount(
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
        $response = new RedirectCount(
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
        $info = new CurlInfo(['redirect_count' => 1]);
        $response = new RedirectCount(
            new CurlResponse(
                'irrelevant',
                [],
                $info,
            ),
        );

        $this->assertSame(
            $info,
            $response->info(),
            'Must proxy info() from origin response',
        );
    }
}
