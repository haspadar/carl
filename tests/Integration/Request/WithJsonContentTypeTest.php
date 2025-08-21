<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\PostRequest;
use Carl\Request\WithJsonBody;
use Carl\Request\WithJsonContentType;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithJsonContentTypeTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    #[Test]
    public function addsJsonContentTypeHeader(): void
    {
        $request = new WithJsonContentType(
            new WithJsonBody(
                new PostRequest($this->server()->url('/reflect')),
                ['hello' => 'world'],
            ),
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedHeader($response, 'content-type', 'application/json');
    }
}
