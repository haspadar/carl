<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Client;

use Carl\Client\ChunkedClient;
use Carl\Client\Fake\FakeClient;
use Carl\Outcome\Fake\AlwaysSuccessful;
use Carl\Outcome\Fake\FakeStatus;
use Carl\Outcome\Outcome;
use Carl\Request\GetRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ChunkedClientTest extends TestCase
{
    #[Test]
    public function returnsOutcomeWhenSingleRequestDelegated(): void
    {
        $client = new ChunkedClient(
            new FakeClient(new AlwaysSuccessful(201, 'X')),
            2,
        );

        $this->assertSame(
            201,
            (int) $client
                ->outcome(new GetRequest('http://example.test/ok'))
                ->response()
                ->info()
                ->value(CURLINFO_RESPONSE_CODE),
            'Must return status code from origin->outcome()'
        );
    }

    #[Test]
    public function returnsOutcomesWhenChunkingByTwo(): void
    {
        $client = new ChunkedClient(
            new FakeClient(new FakeStatus()),
            2
        );

        $statusCodes = array_map(
            fn (Outcome $outcome): int => (int) $outcome->response()->info()->value(CURLINFO_RESPONSE_CODE),
            $client->outcomes([
                new GetRequest('http://localhost/201'),
                new GetRequest('http://localhost/404'),
                new GetRequest('http://localhost/302'),
                new GetRequest('http://localhost/500'),
                new GetRequest('http://localhost/204'),
            ])
        );

        $this->assertSame([201, 404, 302, 500, 204], $statusCodes, 'Must preserve order and merge results correctly');
    }

    #[Test]
    public function returnsEmptyWhenNoRequests(): void
    {
        $client = new ChunkedClient(new FakeClient(new AlwaysSuccessful()), 3);

        $this->assertSame([], $client->outcomes([]), 'Must return empty array for empty input');
    }
}
