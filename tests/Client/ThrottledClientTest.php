<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Client;

use Carl\Client\Fake\FakeClient;
use Carl\Client\ThrottledClient;
use Carl\Outcome\Fake\AlwaysSuccessful;
use Carl\Request\GetRequest;
use Carl\Tests\Fake\Time\FakeDelay;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ThrottledClientTest extends TestCase
{
    #[Test]
    public function sleepsBetweenRequests(): void
    {
        $delay = new FakeDelay();
        $client = new ThrottledClient(
            new FakeClient(new AlwaysSuccessful(200, 'ok')),
            0.01,
            $delay,
        );

        $client->outcomes([
            new GetRequest('http://localhost/1'),
            new GetRequest('http://localhost/2'),
            new GetRequest('http://localhost/3'),
        ]);

        $this->assertSame(
            [10_000, 10_000],
            $delay->calls(),
            'Must sleep N-1 times with exact microseconds',
        );
    }
}
