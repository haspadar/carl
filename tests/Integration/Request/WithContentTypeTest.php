<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\WithContentType;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use JsonException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithContentTypeTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    /**
     * @throws JsonException
     */
    #[Test]
    public function sendsContentTypeHeader(): void
    {
        $request = new WithContentType(
            new GetRequest($this->server()->url('/reflect')),
            'application/json',
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedHeader($response, 'content-type', 'application/json');
    }
}
