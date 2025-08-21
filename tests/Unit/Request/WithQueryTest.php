<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Request;

use Carl\Exception;
use Carl\Request\RawOptionsRequest;
use Carl\Request\WithQuery;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithQueryTest extends TestCase
{
    #[Test]
    public function appendsQueryStringToUrlWithoutExistingQuery(): void
    {
        $origin = new RawOptionsRequest([
            CURLOPT_URL => 'https://example.com/api',
        ]);

        $decorated = new WithQuery($origin, ['foo' => 'bar', 'tags' => ['php', 'curl']]);

        $this->assertSame(
            'https://example.com/api?foo=bar&tags%5B0%5D=php&tags%5B1%5D=curl',
            $decorated->options()[CURLOPT_URL],
        );
    }

    #[Test]
    public function removesExistingQueryWhenParamsEmpty(): void
    {
        $origin = new RawOptionsRequest([
            CURLOPT_URL => 'https://example.com/path?a=1#frag',
        ]);

        $decorated = new WithQuery($origin, []);
        $this->assertSame('https://example.com/path#frag', $decorated->options()[CURLOPT_URL]);
    }

    #[Test]
    public function replacesExistingQueryAndPreservesFragment(): void
    {
        $origin = new RawOptionsRequest([
            CURLOPT_URL => 'https://example.com/search?q=old#section1',
        ]);

        $decorated = new WithQuery($origin, ['q' => 'new', 'page' => 2]);

        $this->assertSame(
            'https://example.com/search?q=new&page=2#section1',
            $decorated->options()[CURLOPT_URL],
        );
    }

    #[Test]
    public function throwsWhenUrlIsMissing(): void
    {
        $this->expectException(Exception::class);

        $origin = new RawOptionsRequest([]);
        new WithQuery($origin, ['x' => 1])->options();
    }

    #[Test]
    public function throwsWhenUrlIsNotAString(): void
    {
        $this->expectException(Exception::class);

        $origin = new RawOptionsRequest([
            CURLOPT_URL => ['not', 'a', 'string'],
        ]);

        new WithQuery($origin, ['x' => 1])->options();
    }
}
