<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Request;

use Carl\Request\RawOptionsRequest;
use Carl\Request\WithConnectionTimeoutMs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithConnectionTimeoutTest extends TestCase
{
    #[Test]
    public function addsConnectionTimeoutOption(): void
    {
        $request = new WithConnectionTimeoutMs(
            new RawOptionsRequest([CURLOPT_RETURNTRANSFER => 1]),
            7
        );

        $options = $request->options();

        $this->assertSame(1, $options[CURLOPT_RETURNTRANSFER]);
        $this->assertSame(7, $options[CURLOPT_TIMEOUT_MS]);
    }
}
