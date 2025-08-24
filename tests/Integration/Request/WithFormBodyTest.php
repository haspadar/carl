<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\PostRequest;
use Carl\Request\WithContentType;
use Carl\Request\WithFormBody;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithFormBodyTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    #[Test]
    public function sendsFormEncodedBody(): void
    {
        $request = new WithFormBody(
            new PostRequest($this->server()->url('/reflect')),
            ['foo' => 'bar', 'baz' => '123']
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedBody($response, 'foo=bar&baz=123');
    }

    #[Test]
    public function overridesFormBodyIfStacked(): void
    {
        $request = new WithFormBody(
            new WithFormBody(
                new PostRequest($this->server()->url('/reflect')),
                ['a' => 1]
            ),
            ['x' => 42, 'y' => 'z']
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedBody($response, 'x=42&y=z');
        $this->assertStringNotContainsString('a=1', $response->body());
    }

    #[Test]
    public function respectsExplicitContentType(): void
    {
        $request = new WithContentType(
            new WithFormBody(
                new PostRequest($this->server()->url('/reflect')),
                ['a' => 'b']
            ),
            'application/x-www-form-urlencoded'
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedBody($response, 'a=b');
        $this->assertReflectedHeader($response, 'content-type', 'application/x-www-form-urlencoded');
    }
}
