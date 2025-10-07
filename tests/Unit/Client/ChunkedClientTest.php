<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Client;

use Carl\Client\ChunkedClient;
use Carl\Client\Fake\FakeClient;
use Carl\Outcome\Fake\AlwaysSuccessful;
use Carl\Outcome\Fake\FakeStatus;
use Carl\Request\GetRequest;
use Carl\Tests\Integration\Support\AssertsHttpResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ChunkedClientTest extends TestCase
{
    use AssertsHttpResponse;

    #[Test]
    public function returnsOutcomeWhenSingleRequestGiven(): void
    {
        $client = new ChunkedClient(
            new FakeClient(new AlwaysSuccessful(201, 'X')),
            2
        );

        $outcome = $client->outcome(new GetRequest('http://example.test/ok'));

        $this->assertStatusCode(
            $outcome->response(),
            201,
            'Must return status code from origin->outcome()'
        );
    }

    #[Test]
    public function returnsOutcomesWhenChunkSizeIsTwo(): void
    {
        $client = new ChunkedClient(
            new FakeClient(new FakeStatus()),
            2
        );

        $outcomes = $client->outcomes([
            new GetRequest('http://localhost/201'),
            new GetRequest('http://localhost/404'),
            new GetRequest('http://localhost/302'),
            new GetRequest('http://localhost/500'),
            new GetRequest('http://localhost/204'),
        ]);

        $this->assertCount(5, $outcomes, 'Must return outcomes for each request');
        $this->assertStatusCode($outcomes[0]->response(), 201);
        $this->assertStatusCode($outcomes[1]->response(), 404);
        $this->assertStatusCode($outcomes[2]->response(), 302);
        $this->assertStatusCode($outcomes[3]->response(), 500);
        $this->assertStatusCode($outcomes[4]->response(), 204);
    }

    #[Test]
    public function returnsEmptyArrayWhenNoRequests(): void
    {
        $client = new ChunkedClient(
            new FakeClient(new AlwaysSuccessful()),
            3
        );

        $this->assertSame([], $client->outcomes([]), 'Must return empty array for empty input');
    }
}
