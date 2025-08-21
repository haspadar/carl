<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\WithCookies;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithCookiesTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    #[Test]
    public function sendsCookiesInRequestHeader(): void
    {
        $request = new WithCookies(
            new GetRequest($this->server()->url('/reflect')),
            'name=value; theme=dark'
        );

        $response = new CurlClient()->outcome($request)->response();
        $reflected = $this->reflected($response->body());

        $this->assertHeader($reflected, 'cookie', 'name=value; theme=dark');
    }
}
