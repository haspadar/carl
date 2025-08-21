<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Exception;
use Carl\Request\GetRequest;
use Carl\Request\WithTimeoutMs;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithTimeoutMsTest extends TestCase
{
    use WithRunningServer;
    use AssertsHttpResponse;

    #[Test]
    public function failsIfRequestExceedsTimeout(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/timed out|Timeout/');

        $request = new WithTimeoutMs(
            new GetRequest($this->server()->url('/sleep/20')),
            1
        );

        new CurlClient()->outcome($request)->response();
    }

    #[Test]
    public function succeedsIfRequestWithinTimeout(): void
    {
        $request = new WithTimeoutMs(
            new GetRequest($this->server()->url('/sleep/1')),
            150,
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertStatusCode($response, 200, 'Expected 200 OK for fast response');
    }
}
