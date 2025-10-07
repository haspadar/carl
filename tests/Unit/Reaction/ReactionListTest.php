<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Reaction;

use Carl\Reaction\ReactionList;
use Carl\Reaction\ReactionOf;
use Carl\Request\GetRequest;
use Carl\Request\Request;
use Carl\Response\Fake\FixedResponse;
use Carl\Response\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ReactionListTest extends TestCase
{
    #[Test]
    public function delegatesOnSuccessToAllReactions(): void
    {
        $firstCounter = 0;
        $secondCounter = 0;

        $list = new ReactionList([
            new ReactionOf(
                function (Request $request, Response $response) use (&$firstCounter): void {
                    $firstCounter++;
                },
                function (Request $request, string $response): void {}
            ),
            new ReactionOf(
                function (Request $request, Response $response) use (&$secondCounter): void {
                    $secondCounter++;
                },
                function (Request $request, string $response): void {}
            ),
        ]);

        $list->onSuccess(
            new GetRequest('http://localhost/'),
            new FixedResponse(200, 'ok')
        );

        $this->assertSame(1, $firstCounter, 'First reaction must be called on success');
        $this->assertSame(1, $secondCounter, 'Second reaction must be called on success');
    }

    #[Test]
    public function delegatesOnFailureToAllReactions(): void
    {
        $firstCounter = 0;
        $secondCounter = 0;

        $list = new ReactionList([
            new ReactionOf(
                function (Request $request, Response $response): void {},
                function (Request $request, string $error) use (&$firstCounter): void {
                    $firstCounter++;
                }
            ),
            new ReactionOf(
                function (Request $request, Response $response): void {},
                function (Request $request, string $error) use (&$secondCounter): void {
                    $secondCounter++;
                }
            ),
        ]);

        $list->onFailure(
            new GetRequest('http://localhost/'),
            'error'
        );

        $this->assertSame(1, $firstCounter, 'First reaction must be called on failure');
        $this->assertSame(1, $secondCounter, 'Second reaction must be called on failure');
    }

    #[Test]
    public function preservesOrderOfReactions(): void
    {
        $calls = [];

        $list = new ReactionList([
            new ReactionOf(
                function (Request $request, Response $response) use (&$calls): void {
                    $calls[] = 'first-success';
                },
                function (Request $request, string $error) use (&$calls): void {
                    $calls[] = 'first-failure';
                }
            ),
            new ReactionOf(
                function (Request $request, Response $response) use (&$calls): void {
                    $calls[] = 'second-success';
                },
                function (Request $request, string $error) use (&$calls): void {
                    $calls[] = 'second-failure';
                }
            ),
        ]);

        $list->onSuccess(
            new GetRequest('http://localhost/'),
            new FixedResponse(200, 'ok')
        );

        $list->onFailure(
            new GetRequest('http://localhost/'),
            'error'
        );

        $this->assertSame(
            ['first-success', 'second-success', 'first-failure', 'second-failure'],
            $calls,
            'Reactions must be executed in the order they were provided'
        );
    }
}
