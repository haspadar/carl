<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\PostRequest;
use Carl\Request\WithBody;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithBodyTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    #[Test]
    public function sendsBody(): void
    {
        $request = new WithBody(
            new PostRequest($this->server()->url('/reflect')),
            'abc=123&x=42'
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedMethod($response, 'POST');
        $this->assertReflectedBody($response, 'abc=123&x=42');
    }
}
