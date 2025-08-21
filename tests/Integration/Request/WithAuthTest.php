<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\WithAuth;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithAuthTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;
    use AssertsHttpResponse;

    #[Test]
    public function sendsBasicAuthHeader(): void
    {
        $request = new WithAuth(
            new GetRequest($this->server()->url('/reflect')),
            'john',
            'secret',
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedHeader(
            $response,
            'authorization',
            'Basic ' . base64_encode('john:secret'),
        );
    }
}
