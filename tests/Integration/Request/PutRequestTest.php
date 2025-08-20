<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\PutRequest;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use JsonException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PutRequestTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;
    use AssertsHttpResponse;

    #[Test]
    /**
     * @throws JsonException
     */
    public function sendsPutRequestToReflectEndpoint(): void
    {
        $response = new CurlClient()->outcome(
            new PutRequest($this->server()->url('/reflect'))
        )->response();

        $this->assertStatusCode($response, 200);
        $this->assertReflectedMethod($response, 'PUT');
    }
}
