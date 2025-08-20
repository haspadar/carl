<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CurlInfoTest extends TestCase
{
    #[Test]
    public function returnsStringValueWhenScalarPresent(): void
    {
        $this->assertSame(
            '200',
            new CurlInfo([CURLINFO_RESPONSE_CODE => 200])->value(CURLINFO_RESPONSE_CODE),
            'Must return scalar value as string',
        );
    }

    #[Test]
    public function returnsDefaultWhenKeyMissing(): void
    {
        $this->assertSame(
            'n/a',
            new CurlInfo([])->value('missing', 'n/a'),
            'Must return default when key is absent or non-scalar',
        );
    }

    #[Test]
    public function returnsHasKeyWhenPresent(): void
    {
        $this->assertTrue(
            new CurlInfo(['x' => 1])->hasKey('x'),
            'Must detect existing key',
        );
    }

    #[Test]
    public function returnsAllWhenRequested(): void
    {
        $this->assertSame(
            ['a' => 1, 'b' => 2],
            new CurlInfo(['a' => 1, 'b' => 2])->all(),
            'Must return full info array',
        );
    }

    #[Test]
    public function returnsDefaultWhenValueIsNonScalar(): void
    {
        $this->assertSame(
            'n/a',
            new CurlInfo(['x' => ['nested']])->value('x', 'n/a'),
            'Must return default when value is non-scalar',
        );
    }
}
