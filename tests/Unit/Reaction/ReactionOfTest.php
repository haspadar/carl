<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Reaction;

use Carl\Reaction\ReactionOf;
use Carl\Request\GetRequest;
use Carl\Request\Request;
use Carl\Response\Fake\SuccessResponse;
use Carl\Response\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ReactionOfTest extends TestCase
{
    #[Test]
    public function callsOnSuccessWhenSuccess(): void
    {
        $called = 0;

        new ReactionOf(
            function (Request $request, Response $response) use (&$called): void {
                $called++;
            },
            function (Request $request, string $error): void {},
        )->onSuccess(
            new GetRequest('http://localhost/'),
            new SuccessResponse('ok'),
        );

        $this->assertSame(1, $called, 'Must call onSuccess callback');
    }

    #[Test]
    public function callsOnFailureWhenFailure(): void
    {
        $called = 0;

        new ReactionOf(
            function (Request $request, Response $response): void {},
            function (Request $request, string $error) use (&$called): void {
                $called++;
            },
        )->onFailure(
            new GetRequest('http://localhost/'),
            'error',
        );

        $this->assertSame(1, $called, 'Must call onFailure callback');
    }
}
