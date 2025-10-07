<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Request;

use Carl\Request\GetRequest;
use Carl\Request\WithHeader;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithHeaderTest extends TestCase
{
    #[Test]
    public function addsHeaderWhenNotExists(): void
    {
        $options = new WithHeader(
            new GetRequest('http://localhost/'),
            'X-Test',
            'Value'
        )->options();

        $this->assertSame(
            ['X-Test: Value'],
            $options[CURLOPT_HTTPHEADER],
            'Must add header when not present'
        );
    }

    #[Test]
    public function replacesHeaderCaseInsensitive(): void
    {
        $base = new GetRequest('http://localhost/');
        $withOld = new WithHeader($base, 'X-Test', 'Old');
        $withNew = new WithHeader($withOld, 'x-test', 'New');

        $this->assertSame(
            ['x-test: New'],
            $withNew->options()[CURLOPT_HTTPHEADER],
            'Must replace existing header ignoring case'
        );
    }

    #[Test]
    public function skipsInvalidHeaders(): void
    {
        $base = new GetRequest('http://localhost/');
        $withInvalid = new WithHeader($base, "Bad\rHeader", "X");
        $withValid = new WithHeader($withInvalid, 'X-Good', 'OK');

        $this->assertSame(
            ['X-Good: OK'],
            $withValid->options()[CURLOPT_HTTPHEADER],
            'Must ignore malformed headers and add only valid one'
        );
    }

    #[Test]
    public function preservesUnrelatedHeaders(): void
    {
        $a = new WithHeader(new GetRequest('http://localhost/'), 'A', '1');
        $b = new WithHeader($a, 'B', '2');
        $c = new WithHeader($b, 'C', '3');

        $this->assertSame(
            ['A: 1', 'B: 2', 'C: 3'],
            $c->options()[CURLOPT_HTTPHEADER],
            'Must preserve unrelated headers'
        );
    }
}
