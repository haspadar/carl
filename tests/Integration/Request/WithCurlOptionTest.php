<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\WithCurlOption;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithCurlOptionTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;
    use AssertsHttpResponse;

    #[Test]
    public function disablesRedirectFollowing(): void
    {
        $request = new WithCurlOption(
            new GetRequest($this->server()->url('/reflect')),
            CURLOPT_HTTPHEADER,
            ['X-Test-Header: hello']
        );

        $response = new CurlClient()->outcome($request)->response();
        $reflected = $this->reflected($response->body());

        $this->assertHeader($reflected, 'x-test-header', 'hello');
    }
}
