<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Reaction;

use Carl\Reaction\OnFailure;
use Carl\Request\GetRequest;
use Carl\Response\Fake\SuccessResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class OnFailureTest extends TestCase
{
    #[Test]
    public function incrementsWhenFailure(): void
    {
        $called = 0;

        new OnFailure(function () use (&$called): void {
            $called++;
        })->onFailure(
            new GetRequest('http://localhost/'),
            'error'
        );

        $this->assertSame(1, $called, 'Must call callback on failure');
    }

    #[Test]
    public function ignoresWhenSuccess(): void
    {
        $called = 0;

        new OnFailure(function () use (&$called): void {
            $called++;
        })->onSuccess(
            new GetRequest('http://localhost/'),
            new SuccessResponse('ok')
        );

        $this->assertSame(0, $called, 'Must not call callback on success');
    }

}
