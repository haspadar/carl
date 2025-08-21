<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\WithStatusCode;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithStatusCodeTest extends TestCase
{
    use AssertsHttpResponse;

    #[Test]
    public function overridesStatusCodeWhenWrapped(): void
    {
        $response = new WithStatusCode(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo(['http_code' => 500])
            ),
            201
        );
        $this->assertStatusCode(
            $response,
            201,
            'Should override status code in info()'
        );
    }

    #[Test]
    public function returnsBodyFromOriginWhenCalled(): void
    {
        $response = new WithStatusCode(
            new CurlResponse('expected body', [], new CurlInfo([])),
            201
        );

        $this->assertSame(
            'expected body',
            $response->body(),
            'Should delegate body() to origin'
        );
    }

    #[Test]
    public function returnsHeadersFromOriginWhenCalled(): void
    {
        $response = new WithStatusCode(
            new CurlResponse('irrelevant', ['X-Foo' => 'Bar'], new CurlInfo([])),
            201
        );

        $this->assertSame(
            ['X-Foo' => 'Bar'],
            $response->headers(),
            'Should delegate headers() to origin'
        );
    }
}