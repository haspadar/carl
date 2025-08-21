<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Client\CurlClient;
use Carl\Request\GetRequest;
use Carl\Request\WithFollowRedirects;
use Carl\Tests\Integration\Support\AssertsReflectedResponse;
use Carl\Tests\Integration\Support\WithRunningServer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithFollowRedirectsTest extends TestCase
{
    use WithRunningServer;
    use AssertsReflectedResponse;

    #[Test]
    public function followsRedirectAutomatically(): void
    {
        $request = new WithFollowRedirects(
            new GetRequest($this->server()->url('/redirect/302'))
        );

        $response = new CurlClient()->outcome($request)->response();

        $this->assertReflectedPath($response, '/reflect');
    }

    #[Test]
    public function failsWhenRedirectLimitExceeded(): void
    {
        $request = new WithFollowRedirects(
            new GetRequest($this->server()->url('/redirect-twice')),
            1,
        );

        $outcome = new CurlClient()->outcome($request);

        $this->assertFalse($outcome->isSuccessful(), 'Should fail after exceeding max redirects');
    }
}
