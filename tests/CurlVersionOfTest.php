<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests;

use Carl\CurlVersionOf;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class CurlVersionOfTest extends TestCase
{
    #[Test]
    #[TestWith([['version' => '8.5.0'], '8.5.0'])]
    #[TestWith([['ssl_version' => 'OpenSSL/3.0.0'], 'unknown'])]
    #[TestWith([['version' => 80500], 'unknown'])]
    #[TestWith([false, 'unknown'])]
    #[TestWith([[], 'unknown'])]
    public function returnsExpectedValue(array|false $input, string $expected): void
    {
        $this->assertSame(
            $expected,
            new CurlVersionOf($input)->value(),
            'CurlVersionOf should return correct version string or "unknown"'
        );
    }
}
