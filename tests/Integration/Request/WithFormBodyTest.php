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

        $this->assertSame(
            ['foo' => 'bar', 'baz' => '123'],
            $this->parsedFormBody($response)
        );
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
        $parsed = $this->parsedFormBody($response);

        $this->assertSame(['x' => '42', 'y' => 'z'], $parsed);
        $this->assertArrayNotHasKey('a', $parsed);
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

        $this->assertSame(['a' => 'b'], $this->parsedFormBody($response));
        $this->assertReflectedHeader($response, 'content-type', 'application/x-www-form-urlencoded');
    }

    #[Test]
    public function encodesUnicodeAndSpaces(): void
    {
        $request = new WithFormBody(
            new PostRequest($this->server()->url('/reflect')),
            ['q' => 'foo bar', 'emoji' => '💙']
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertSame(
            ['q' => 'foo bar', 'emoji' => '💙'],
            $this->parsedFormBody($response)
        );
    }

    #[Test]
    public function encodesNestedArraysAsBracketNotation(): void
    {
        $request = new WithFormBody(
            new PostRequest($this->server()->url('/reflect')),
            ['a' => ['b' => 'c']]
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertStringContainsString('a%5Bb%5D=c', $this->reflected($response->body())['body']);
    }

    #[Test]
    public function allowsEmptyPayload(): void
    {
        $request = new WithFormBody(
            new PostRequest($this->server()->url('/reflect')),
            []
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertSame([], $this->parsedFormBody($response));
    }
}
