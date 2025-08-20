<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Outcome;

use Carl\Outcome\SuccessfulOutcome;
use Carl\Request\GetRequest;
use Carl\Response\Fake\SuccessResponse;
use Carl\Tests\Unit\Reaction\Fake\FakeSuccess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SuccessfulOutcomeTest extends TestCase
{
    #[Test]
    public function returnsResponse(): void
    {
        $response = new SuccessResponse('ok');
        $outcome = new SuccessfulOutcome(
            new GetRequest('http://localhost/'),
            $response
        );

        $this->assertSame($response, $outcome->response(), 'Must return same response');
    }

    #[Test]
    public function reactsOnSuccess(): void
    {
        $reaction = new FakeSuccess();
        $outcome = new SuccessfulOutcome(
            new GetRequest('http://localhost/'),
            new SuccessResponse('ok')
        );

        $outcome->react($reaction);

        $this->assertSame(
            1,
            $reaction->total(),
            'Must call onSuccess on given reaction'
        );
    }

    #[Test]
    public function returnsRequest(): void
    {
        $request = new GetRequest('http://localhost/');
        $outcome = new SuccessfulOutcome($request, new SuccessResponse('ok'));

        $this->assertSame(
            $request,
            $outcome->request(),
            'SuccessfulOutcome::request must return the original request'
        );
    }
}
