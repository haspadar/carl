<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\PrimaryIp;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PrimaryIpTest extends TestCase
{
    #[Test]
    public function returnsPrimaryIpIfSet(): void
    {
        $response = new CurlResponse(
            body: '...',
            headers: [],
            curlInfo: new CurlInfo([
                CURLINFO_PRIMARY_IP => '192.168.1.100',
            ]),
        );

        $this->assertSame(
            '192.168.1.100',
            new PrimaryIp($response)->value(),
            'Should return IP from response info',
        );
    }

    #[Test]
    public function returnsFallbackIfPrimaryIpMissing(): void
    {
        $response = new CurlResponse(
            body: '...',
            headers: [],
            curlInfo: new CurlInfo([]),
        );

        $this->assertSame(
            '0.0.0.0',
            new PrimaryIp($response)->value(),
            'Should return fallback IP if not set',
        );
    }

    #[Test]
    public function returnsIpv6IfSet(): void
    {
        $response = new CurlResponse(
            body: '...',
            headers: [],
            curlInfo: new CurlInfo([CURLINFO_PRIMARY_IP => '2001:db8::1']),
        );

        $this->assertSame('2001:db8::1', new PrimaryIp($response)->value());
    }
}
