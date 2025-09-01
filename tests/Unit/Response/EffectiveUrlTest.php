<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\EffectiveUrl;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class EffectiveUrlTest extends TestCase
{
    #[Test]
    public function returnsEffectiveUrl(): void
    {
        $url = 'https://example.com/final';

        $response = new CurlResponse(
            body: 'Redirected',
            headers: ['Content-Type' => 'text/html'],
            curlInfo: new CurlInfo([
                'url'       => $url,
                'http_code' => 200,
            ]),
        );

        $this->assertSame(
            $url,
            new EffectiveUrl($response)->value(),
            'Effective URL should match',
        );
    }


    #[Test]
    public function returnsEmptyStringIfEffectiveUrlMissing(): void
    {
        $response = new CurlResponse(
            body: '...',
            headers: [],
            curlInfo: new CurlInfo([])
        );

        $this->assertSame(
            '',
            new EffectiveUrl($response)->value(),
            'Should return empty string when effective URL is not set',
        );
    }
}
