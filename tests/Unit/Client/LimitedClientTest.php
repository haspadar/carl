<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Client;

use Carl\Client\Fake\FakeClient;
use Carl\Client\LimitedClient;
use Carl\Outcome\Fake\AlwaysSuccessful;
use Carl\Outcome\Fake\FakeStatus;
use Carl\Request\GetRequest;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class LimitedClientTest extends TestCase
{
    use AssertsHttpResponse;

    #[Test]
    public function delegatesOutcomeToOrigin(): void
    {
        $client = new LimitedClient(
            new FakeClient(new AlwaysSuccessful(202, 'pong')),
            1
        );

        $outcome = $client->outcome(new GetRequest('http://localhost/ping'));

        $this->assertStatusCode(
            $outcome->response(),
            202,
            'Must delegate outcome() to origin client'
        );
    }

    #[Test]
    public function stopsAfterLimitReached(): void
    {
        $client = new LimitedClient(
            new FakeClient(new FakeStatus()),
            2
        );

        $requests = [
            new GetRequest('http://localhost/201'),
            new GetRequest('http://localhost/404'),
            new GetRequest('http://localhost/302'),
        ];

        $outcomes = $client->outcomes($requests);

        $this->assertCount(2, $outcomes, 'Must only execute first N requests when limit is set');

        $this->assertStatusCode($outcomes[0]->response(), 201);
        $this->assertStatusCode($outcomes[1]->response(), 404);
    }

    #[Test]
    public function emptyInputReturnsEmptyArray(): void
    {
        $client = new LimitedClient(new FakeClient(new AlwaysSuccessful()), 3);

        $this->assertSame([], $client->outcomes([]), 'Must return empty array for empty input');
    }

    #[Test]
    public function limitLargerThanRequestsExecutesAll(): void
    {
        $client = new LimitedClient(new FakeClient(new FakeStatus()), 10);

        $requests = [
            new GetRequest('http://localhost/201'),
            new GetRequest('http://localhost/500'),
        ];

        $outcomes = $client->outcomes($requests);

        $this->assertCount(2, $outcomes, 'Must execute all requests if less than limit');

        $this->assertStatusCode($outcomes[0]->response(), 201);
        $this->assertStatusCode($outcomes[1]->response(), 500);
    }
}
