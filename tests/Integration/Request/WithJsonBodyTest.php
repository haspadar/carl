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
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithJsonBodyTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    #[Test]
    public function sendsJsonEncodedBody(): void
    {
        $request = new WithJsonBody(
            new PostRequest($this->server()->url('/reflect')),
            ['foo' => 'bar'],
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedBody($response, '{"foo":"bar"}');
    }

    #[Test]
    public function overridesPreviousJsonBody(): void
    {
        $request = new WithJsonBody(
            new WithJsonBody(
                new PostRequest($this->server()->url('/reflect')),
                ['legacy' => 'payload']
            ),
            ['alpha' => 1, 'beta' => 2],
        );

        $response = new CurlClient()->outcome($request)->response();
        $this->assertReflectedBody($response, '{"alpha":1,"beta":2}');
        $this->assertStringNotContainsString('legacy', $this->reflected($response->body())['body'] ?? '');
    }
}
