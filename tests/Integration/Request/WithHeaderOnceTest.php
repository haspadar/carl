<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\RawOptionsRequest;
use Carl\Request\WithHeaderOnce;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithHeaderOnceTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;
    use AssertsHttpResponse;

    #[Test]
    public function addsHeaderIfNotPresent(): void
    {
        $request = new WithHeaderOnce(
            new GetRequest($this->server()->url('/reflect')),
            'X-Custom-Header',
            'abc123'
        );

        $response = new CurlClient()->outcome($request)->response();
        $this->assertReflectedHeader($response, 'x-custom-header', 'abc123');
    }

    #[Test]
    public function doesNotDuplicateHeaderIfAlreadyPresent(): void
    {
        $request = new WithHeaderOnce(
            new RawOptionsRequest([
                CURLOPT_URL => $this->server()->url('/reflect'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ['X-Foo: bar'],
            ]),
            'X-Foo',
            'bar'
        );

        $response = new CurlClient()->outcome($request)->response();
        $this->assertReflectedHeader($response, 'x-foo', 'bar');
    }

    #[Test]
    public function addsHeaderOnlyOnceEvenIfWrappedTwice(): void
    {
        $url = $this->server()->url('/reflect');
        $request = new WithHeaderOnce(
            new WithHeaderOnce(
                new GetRequest($url),
                'X-Test',
                'once'
            ),
            'X-Test',
            'once'
        );

        $response = new CurlClient()->outcome($request)->response();
        $this->assertReflectedHeader($response, 'x-test', 'once');
    }
}
