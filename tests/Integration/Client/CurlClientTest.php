<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Client;

use Carl\Client\CurlClient;
use Carl\Exception;
use Carl\Request\GetRequest;
use Carl\Tests\Integration\Support\WithRunningServer;
use JsonException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CurlClientTest extends TestCase
{
    use WithRunningServer;

    /**
     * @throws JsonException
     */
    #[Test]
    public function sendsPostRequest(): void
    {
        $response = new CurlClient()->outcome(
            new GetRequest($this->server->url('/status/204'))
        )->response();

        $this->assertSame(
            204,
            (int) $response->info()->value('http_code'),
            'Expected status code 204 from /status/204'
        );
    }

    #[Test]
    public function returnsFailedOutcomeWhenCurlError(): void
    {
        $outcome = new CurlClient()->outcome(
            new GetRequest('http://localhost:9999/nowhere')
        );

        $this->expectException(Exception::class);

        $outcome->response();
    }
}
