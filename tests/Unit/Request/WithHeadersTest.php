<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Request;

use Carl\Request\RawOptionsRequest;
use Carl\Request\WithHeaders;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithHeadersTest extends TestCase
{
    #[Test]
    public function filtersOutNonStringHeaders(): void
    {
        $origin = new RawOptionsRequest([
            CURLOPT_HTTPHEADER => [
                'X-Good: yes',
                null,
                42,
                ['bad'],
                false,
            ],
        ]);

        $request = new WithHeaders($origin, ['X-Another: true']);

        $this->assertSame(
            ['X-Good: yes', 'X-Another: true'],
            $request->options()[CURLOPT_HTTPHEADER],
            'Must filter out non-string headers'
        );
    }

    #[Test]
    public function skipsHeadersWithCrLf(): void
    {
        $origin = new RawOptionsRequest([CURLOPT_HTTPHEADER => ['X-Ok: Yes']]);

        $request = new WithHeaders($origin, [
            "X-Bad: Inject\r\nX-Hacked: Evil",
            "X-Good: Clean",
            "X-Evil: Break\nAnother"
        ]);

        $opts = $request->options();

        $this->assertSame([
            'X-Ok: Yes',
            'X-Good: Clean',
        ], $opts[CURLOPT_HTTPHEADER]);
    }

    #[Test]
    public function mergesWithExistingHeaders(): void
    {
        $origin = new RawOptionsRequest([
            CURLOPT_HTTPHEADER => ['X-Foo: 1']
        ]);

        $decorated = new WithHeaders($origin, ['X-Bar: 2', 'X-Baz: 3']);

        $this->assertSame(
            ['X-Foo: 1', 'X-Bar: 2', 'X-Baz: 3'],
            $decorated->options()[CURLOPT_HTTPHEADER],
            'Must merge origin and new headers'
        );
    }

    #[Test]
    public function skipsCrLfInExistingHeaders(): void
    {
        $origin = new RawOptionsRequest([
            CURLOPT_HTTPHEADER => ["X-Bad: a\r\nX-Injected: yes", "X-Ok: y"]
        ]);

        $opts = new WithHeaders($origin, ['X-New: z'])->options();

        $this->assertSame(['X-Ok: y', 'X-New: z'], $opts[CURLOPT_HTTPHEADER]);
    }
}
