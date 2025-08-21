<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\WithContentType;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithContentTypeTest extends TestCase
{
    use AssertsHttpResponse;
    use AssertsReflectedResponse;
    #[Test]
    public function returnsExpectedContentTypeHeaderWhenWrapped(): void
    {
        $response = new WithContentType(
            new CurlResponse('body', ['X-Foo' => 'Bar'], new CurlInfo([])),
            'application/json'
        );

        $this->assertSame(
            'application/json',
            $response->headers()['Content-Type'],
            'Should return expected Content-Type header'
        );
    }

    #[Test]
    public function returnsBodyFromOriginWhenCalled(): void
    {
        $response = new WithContentType(
            new CurlResponse('my-body', [], new CurlInfo([])),
            'text/plain'
        );

        $this->assertSame('my-body', $response->body(), 'Expected body from origin to be preserved');
    }

    #[Test]
    public function delegatesInfoToOriginWhenCalled(): void
    {
        $response = new WithContentType(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo(['http_code' => 201])
            ),
            'text/plain'
        );
        $this->assertStatusCode(
            $response,
            201,
            'Should delegate info to origin'
        );
    }
}
