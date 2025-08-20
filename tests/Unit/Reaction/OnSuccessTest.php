<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Reaction;

use Carl\Reaction\OnSuccess;
use Carl\Request\GetRequest;
use Carl\Response\Fake\SuccessResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class OnSuccessTest extends TestCase
{
    #[Test]
    public function incrementsWhenSuccess(): void
    {
        $called = 0;

        new OnSuccess(function () use (&$called): void {
            $called++;
        })->onSuccess(
            new GetRequest('http://localhost/'),
            new SuccessResponse('ok')
        );

        $this->assertSame(1, $called, 'Must call callback on success');
    }

    #[Test]
    public function ignoresWhenFailure(): void
    {
        $called = 0;

        new OnSuccess(function () use (&$called): void {
            $called++;
        })->onFailure(
            new GetRequest('http://localhost/'),
            'error'
        );

        $this->assertSame(0, $called, 'Must not call callback on failure');
    }
}
