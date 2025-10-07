<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Outcome;

use Carl\Outcome\SuccessfulOutcome;
use Carl\Request\GetRequest;
use Carl\Response\Fake\FixedResponse;
use Carl\Response\Fake\SuccessResponse;
use Carl\Tests\Unit\Reaction\Fake\FakeSuccess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SuccessfulOutcomeTest extends TestCase
{
    #[Test]
    public function returnsResponseWhenWrapped(): void
    {
        $response = new SuccessResponse(new FixedResponse(200, 'ok'));
        $outcome = new SuccessfulOutcome(
            new GetRequest('http://localhost/'),
            $response
        );

        $this->assertSame($response, $outcome->response(), 'Must return same response');
    }

    #[Test]
    public function callsOnSuccessReactionWhenReacted(): void
    {
        $reaction = new FakeSuccess();
        $outcome = new SuccessfulOutcome(
            new GetRequest('http://localhost/'),
            new SuccessResponse(new FixedResponse(200, 'ok'))
        );

        $outcome->react($reaction);

        $this->assertSame(1, $reaction->total(), 'Must call onSuccess on given reaction');
    }

    #[Test]
    public function returnsOriginalRequestWhenCalled(): void
    {
        $request = new GetRequest('http://localhost/');
        $outcome = new SuccessfulOutcome(
            $request,
            new SuccessResponse(new FixedResponse(200, 'ok'))
        );

        $this->assertSame($request, $outcome->request(), 'Must return the original request');
    }

    #[Test]
    public function isSuccessfulAlwaysReturnsTrue(): void
    {
        $this->assertTrue(
            new SuccessfulOutcome(
                new GetRequest('http://localhost/'),
                new SuccessResponse(new FixedResponse(200, 'ok'))
            )->isSuccessful(),
            'SuccessfulOutcome must always be successful'
        );
    }
}
