<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\WithUserAgent;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithUserAgentTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    #[Test]
    public function sendsUserAgentHeader(): void
    {
        $userAgent = 'MyApp/1.0';

        $request = new WithUserAgent(
            new GetRequest($this->server()->url('/reflect')),
            $userAgent,
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedHeader($response, 'user-agent', $userAgent);
    }
}
