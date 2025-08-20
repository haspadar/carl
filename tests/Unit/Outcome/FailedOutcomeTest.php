<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Outcome;

use Carl\Exception;
use Carl\Outcome\FailedOutcome;
use Carl\Request\GetRequest;
use Carl\Tests\Unit\Reaction\Fake\FakeFailure;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FailedOutcomeTest extends TestCase
{
    #[Test]
    public function reactsOnFailure(): void
    {
        $reaction = new FakeFailure();
        $outcome = new FailedOutcome(
            new GetRequest('http://localhost/'),
            'network error'
        );

        $outcome->react($reaction);

        $this->assertSame(
            1,
            $reaction->total(),
            'Must call onFailure on given reaction'
        );
    }

    #[Test]
    public function throwsWhenResponseCalled(): void
    {
        $outcome = new FailedOutcome(
            new GetRequest('http://localhost/'),
            'timeout'
        );

        $this->expectException(Exception::class);

        $outcome->response();
    }

    #[Test]
    public function returnsRequest(): void
    {
        $request = new GetRequest('http://localhost/');
        $outcome = new FailedOutcome($request, 'error');

        $this->assertSame(
            $request,
            $outcome->request(),
            'FailedOutcome::request must return the original request'
        );
    }
}
