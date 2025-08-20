<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\DeleteRequest;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\Server\PhpServer;
use Carl\Tests\Integration\Support\Server\RunningServer;
use Carl\Tests\Integration\Support\WithRunningServer;
use JsonException;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Random\RandomException;

final class DeleteRequestTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    /**
     * @throws JsonException
     */
    #[Test]
    public function sendsDeleteRequestToReflectEndpoint(): void
    {
        $response = new CurlClient()->outcome(
            new DeleteRequest($this->server->url('/reflect'))
        )->response();

        $this->assertReflectedMethod($response, 'DELETE');
    }
}
