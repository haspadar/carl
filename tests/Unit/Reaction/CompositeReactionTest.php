<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Reaction;

use Carl\Reaction\CompositeReaction;
use Carl\Reaction\ReactionOf;
use Carl\Request\GetRequest;
use Carl\Response\Fake\SuccessResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CompositeReactionTest extends TestCase
{
    #[Test]
    public function delegatesOnSuccessToAllReactions(): void
    {
        $firstCounter = 0;
        $secondCounter = 0;

        $composite = new CompositeReaction([
            new ReactionOf(
                function () use (&$firstCounter): void {
                    $firstCounter++;
                },
                function (): void {}
            ),
            new ReactionOf(
                function () use (&$secondCounter): void {
                    $secondCounter++;
                },
                function (): void {}
            ),
        ]);

        $composite->onSuccess(
            new GetRequest('http://localhost/'),
            new SuccessResponse('ok')
        );

        $this->assertSame(1, $firstCounter, 'First reaction must be called on success');
        $this->assertSame(1, $secondCounter, 'Second reaction must be called on success');
    }

    #[Test]
    public function delegatesOnFailureToAllReactions(): void
    {
        $firstCounter = 0;
        $secondCounter = 0;

        $composite = new CompositeReaction([
            new ReactionOf(
                function (): void {},
                function () use (&$firstCounter): void {
                    $firstCounter++;
                }
            ),
            new ReactionOf(
                function (): void {},
                function () use (&$secondCounter): void {
                    $secondCounter++;
                }
            ),
        ]);

        $composite->onFailure(
            new GetRequest('http://localhost/'),
            'error'
        );

        $this->assertSame(1, $firstCounter, 'First reaction must be called on failure');
        $this->assertSame(1, $secondCounter, 'Second reaction must be called on failure');
    }
}
