<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\WithHeaderIncluded;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithHeaderIncludedTest extends TestCase
{
    use WithRunningServer;

    #[Test]
    public function includesHeadersInRawResponse(): void
    {
        $request = new WithHeaderIncluded(
            new GetRequest($this->server()->url('/reflect'))
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertArrayHasKey('Content-Type', $response->headers());
    }

    #[Test]
    public function doesNotIncludeHeadersByDefault(): void
    {
        $request = new GetRequest($this->server()->url('/reflect'));

        $response = new CurlClient()->outcome($request)->response();

        $this->assertSame([], $response->headers());
    }
}
