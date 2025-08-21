<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\WithDefaultUserAgent;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithDefaultUserAgentTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    #[Test]
    public function setsDefaultUserAgentHeader(): void
    {
        $request = new WithDefaultUserAgent(
            new GetRequest($this->server()->url('/reflect'))
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedHeaderContains($response, 'user-agent', 'Carl');
    }
}
