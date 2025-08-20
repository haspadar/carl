<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\PatchRequest;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use JsonException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PatchRequestTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    #[Test]
    /**
     * @throws JsonException
     */
    public function sendsPatchRequestToReflectEndpoint(): void
    {
        $response = new CurlClient()->outcome(
            new PatchRequest($this->server()->url('/reflect')),
        )->response();

        $this->assertReflectedMethod($response, 'PATCH');
    }
}
