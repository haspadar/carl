<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Request;

use Carl\Request\RawOptionsRequest;
use Carl\Request\WithHeader;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithHeaderTest extends TestCase
{
    #[Test]
    public function addsNewHeader(): void
    {
        $request = new RawOptionsRequest([
            CURLOPT_HTTPHEADER => ['X-Foo: 1'],
        ]);

        $decorated = new WithHeader($request, 'X-Bar', '2');

        $this->assertEqualsCanonicalizing(
            ['X-Foo: 1', 'X-Bar: 2'],
            $decorated->options()[CURLOPT_HTTPHEADER] ?? []
        );
    }

    #[Test]
    public function replacesMatchingHeader(): void
    {
        $request = new RawOptionsRequest([
            CURLOPT_HTTPHEADER => ['X-Foo: 1', 'X-Foo: 2'],
        ]);

        $decorated = new WithHeader($request, 'X-Foo', '9');

        $this->assertEqualsCanonicalizing(
            ['X-Foo: 9'],
            $decorated->options()[CURLOPT_HTTPHEADER] ?? []
        );
    }

    #[Test]
    public function replacesOnlyMatchingHeader(): void
    {
        $request = new RawOptionsRequest([
            CURLOPT_HTTPHEADER => ['X-Foo: 1', 'X-Bar: 2'],
        ]);

        $decorated = new WithHeader($request, 'X-Foo', '9');

        $this->assertEqualsCanonicalizing(
            ['X-Foo: 9', 'X-Bar: 2'],
            $decorated->options()[CURLOPT_HTTPHEADER] ?? []
        );
    }

    #[Test]
    public function headerNameMatchIsCaseInsensitive(): void
    {
        $request = new RawOptionsRequest([
            CURLOPT_HTTPHEADER => ['x-foo: 1'],
        ]);

        $decorated = new WithHeader($request, 'X-Foo', '9');

        $this->assertEqualsCanonicalizing(
            ['X-Foo: 9'],
            $decorated->options()[CURLOPT_HTTPHEADER] ?? []
        );
    }

    #[Test]
    public function setsHeaderIfNonePresent(): void
    {
        $request = new RawOptionsRequest([]);

        $decorated = new WithHeader($request, 'X-New', 'value');

        $this->assertEqualsCanonicalizing(
            ['X-New: value'],
            $decorated->options()[CURLOPT_HTTPHEADER] ?? []
        );
    }
}
