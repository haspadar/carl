<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\RedirectUrl;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RedirectUrlTest extends TestCase
{
    #[Test]
    public function returnsRedirectUrlWhenPresent(): void
    {
        $url = 'https://example.com/final';

        $response = new RedirectUrl(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo(['redirect_url' => $url]),
            ),
        );

        $this->assertSame(
            $url,
            $response->value(),
            'Must return redirect_url value from CurlInfo when present',
        );
    }

    #[Test]
    public function returnsEmptyStringWhenMissing(): void
    {
        $response = new RedirectUrl(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo([]),
            ),
        );

        $this->assertSame(
            '',
            $response->value(),
            'Must return empty string when redirect_url key missing in CurlInfo',
        );
    }

    #[Test]
    public function proxiesBodyFromOrigin(): void
    {
        $response = new RedirectUrl(
            new CurlResponse('body-data', [], new CurlInfo([])),
        );

        $this->assertSame(
            'body-data',
            $response->body(),
            'Must proxy body() from origin response',
        );
    }

    #[Test]
    public function proxiesHeadersFromOrigin(): void
    {
        $response = new RedirectUrl(
            new CurlResponse('irrelevant', ['X-Test' => '1'], new CurlInfo([])),
        );

        $this->assertSame(
            ['X-Test' => '1'],
            $response->headers(),
            'Must proxy headers() from origin response',
        );
    }

    #[Test]
    public function proxiesInfoFromOrigin(): void
    {
        $info = new CurlInfo(['redirect_url' => 'https://redirect']);
        $response = new RedirectUrl(
            new CurlResponse('irrelevant', [], $info),
        );

        $this->assertSame(
            $info,
            $response->info(),
            'Must proxy info() from origin response',
        );
    }
}
