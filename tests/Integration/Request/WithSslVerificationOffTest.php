<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\WithSslVerificationOff;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithSslVerificationOffTest extends TestCase
{
    use WithRunningServer;
    use AssertsHttpResponse;

    #[Test]
    public function disablesSslVerification(): void
    {
        $request = new WithSslVerificationOff(
            new GetRequest($this->server()->url('/reflect'))
        );

        $outcome = new CurlClient()->outcome($request);

        $this->assertStatusCode(
            $outcome->response(),
            200,
            'Should return 200 even with SSL verification off'
        );
    }
}
