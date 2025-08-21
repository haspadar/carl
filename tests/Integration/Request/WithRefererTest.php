<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\WithReferer;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithRefererTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    #[Test]
    public function sendsRefererHeader(): void
    {
        $referer = 'https://ref.example';
        $request = new WithReferer(
            new GetRequest($this->server()->url('/reflect')),
            $referer,
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedHeader($response, 'referer', $referer);
    }
}
