<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Request;

use Carl\Request\RawOptionsRequest;
use Carl\Request\WithCurlOption;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithCurlOptionTest extends TestCase
{
    #[Test]
    public function setsCustomOption(): void
    {
        $options = new WithCurlOption(
            new RawOptionsRequest([]),
            CURLOPT_TIMEOUT,
            10
        )->options();

        $this->assertSame(10, $options[CURLOPT_TIMEOUT]);
    }
}
