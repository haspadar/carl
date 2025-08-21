<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\WithHttpVersion;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithHttpVersionTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    /**
     * @throws \JsonException
     */
    #[Test]
    public function usesValidHttpVersion(): void
    {
        $version = CURL_HTTP_VERSION_NONE;
        $request = new WithHttpVersion(
            new GetRequest($this->server()->url('/reflect')),
            $version
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedMethod($response, 'GET');
    }
}
