<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Client;

use Carl\Client\Fake\FakeClient;
use Carl\Client\ThrottledClient;
use Carl\Exception;
use Carl\Outcome\Fake\AlwaysSuccessful;
use Carl\Request\GetRequest;
use Carl\Tests\Unit\Fake\Time\FakeDelay;
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

    #[Test]
    public function noSleepForEmpty(): void
    {
        $delay = new FakeDelay();
        new ThrottledClient(new FakeClient(new AlwaysSuccessful()), 0.01, $delay)
            ->outcomes([]);

        $this->assertSame([], $delay->calls(), 'No sleeps expected for empty requests');
    }

    #[Test]
    public function noSleepForSingleRequest(): void
    {
        $delay = new FakeDelay();
        new ThrottledClient(
            new FakeClient(
                new AlwaysSuccessful()
            ),
            0.01,
            $delay
        )
            ->outcomes([new GetRequest('http://localhost/only')]);

        $this->assertSame([], $delay->calls(), 'No sleeps expected for single request');
    }

    #[Test]
    public function zeroDelaySkipsSleeping(): void
    {
        $delay = new FakeDelay();
        new ThrottledClient(
            new FakeClient(
                new AlwaysSuccessful()
            ),
            0.0,
            $delay
        )
            ->outcomes([
                new GetRequest('http://localhost/1'),
                new GetRequest('http://localhost/2'),
            ]);

        $this->assertSame([], $delay->calls(), 'Zero delay must not sleep');
    }

    #[Test]
    public function rejectsInvalidDelays(): void
    {
        $this->expectException(Exception::class);
        new ThrottledClient(new FakeClient(new AlwaysSuccessful()), -0.1);
    }

    #[Test]
    public function outcomeDelegatesToOrigin(): void
    {
        $fake = new FakeClient(new AlwaysSuccessful(200, 'pong'));
        $client = new ThrottledClient($fake, 0.01);

        $outcome = $client->outcome(new GetRequest('http://localhost/ping'));

        $this->assertSame(
            'pong',
            $outcome->response()->body(),
            'ThrottledClient must delegate outcome() to origin client'
        );
    }


}
