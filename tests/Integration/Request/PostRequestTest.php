<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\PatchRequest;
use Carl\Request\PostRequest;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PostRequestTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    #[Test]
    public function sendsPostRequestWithBody(): void
    {
        $response = new CurlClient()->outcome(
            new PostRequest($this->server->url('/reflect'), 'hello')
        )->response();

        $this->assertReflectedMethod($response, 'POST');
        $this->assertReflectedBody($response, 'hello');
    }
}