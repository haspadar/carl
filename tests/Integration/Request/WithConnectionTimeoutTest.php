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
use Carl\Request\WithConnectionTimeoutMs;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithConnectionTimeoutTest extends TestCase
{
    use AssertsHttpResponse;

    #[Test]
    /**
     * Ensures that the connection times out quickly if the host is unreachable.
     *
     * The IP address 10.255.255.1 is non-routable and guaranteed not to respond.
     * We use it here to simulate a connection timeout scenario.
     */
    public function failsFastWhenConnectionTimeoutExceeded(): void
    {
        $request = new WithConnectionTimeoutMs(
            new GetRequest('http://10.255.255.1/'),
            1
        );

        $client = new CurlClient();

        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/timed out|Timeout|Resolving/i');

        $client->outcome($request)->response();
    }
}
