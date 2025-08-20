<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\RawOptionsRequest;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use JsonException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RawOptionsRequestTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;
    use AssertsHttpResponse;

    #[Test]
    /**
     * @throws JsonException
     */
    public function appliesRawOptionsToCurlRequest(): void
    {
        $response = new CurlClient()->outcome(
            new RawOptionsRequest([
                CURLOPT_URL => $this->server()->url('/reflect'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ])
        )->response();

        $this->assertReflectedMethod($response, 'GET');
        $this->assertStatusCode($response, 200, 'Reflect endpoint should return HTTP 200');
    }
}
