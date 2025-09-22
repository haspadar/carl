<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\Fake\WithHeaderDefaults;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithHeaderDefaultsTest extends TestCase
{
    #[Test]
    public function addsDefaultHeaders(): void
    {
        $response = new WithHeaderDefaults(
            new CurlResponse('abc', [], new CurlInfo([])),
        );

        $this->assertEqualsCanonicalizing(
            ['Content-Type', 'Content-Length', 'Server', 'Connection', 'Date'],
            array_keys($response->headers()),
            'Headers must contain the default keys',
        );
    }

    #[Test]
    public function originHeadersOverrideDefaults(): void
    {
        $response = new WithHeaderDefaults(
            new CurlResponse('x', ['Content-Type' => 'application/json'], new CurlInfo([])),
        );

        $headers = $response->headers();

        $this->assertSame('application/json', $headers['Content-Type'], 'Origin header should override default');
    }
}
