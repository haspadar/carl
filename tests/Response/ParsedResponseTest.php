<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Response;

use Carl\Response\ParsedResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ParsedResponseTest extends TestCase
{
    #[Test]
    public function returnsLastHeaderBlockWhenMultiplePresent(): void
    {
        $raw = "HTTP/1.1 301 Moved\r\nH1: a\r\n\r\n" .
            "HTTP/1.1 200 OK\r\nH2: b\r\n\r\nBODY";
        $this->assertSame(
            "HTTP/1.1 200 OK\r\nH2: b",
            new ParsedResponse($raw)->lastHeaderBlock(),
            'Must extract the last header block'
        );
    }

    #[Test]
    public function returnsHeadersWhenParsed(): void
    {
        $raw = "HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\nX-Id: 7\r\n\r\nok";
        $this->assertSame(
            ['Content-Type' => 'text/plain', 'X-Id' => '7'],
            new ParsedResponse($raw)->headers(),
            'Must parse header lines into an associative array'
        );
    }

    #[Test]
    public function returnsBodyWhenHeadersPresent(): void
    {
        $raw = "HTTP/1.1 200 OK\r\nA: 1\r\n\r\nHello";
        $this->assertSame(
            'Hello',
            new ParsedResponse($raw)->body(),
            'Must return body after the last header block'
        );
    }

    #[Test]
    public function returnsRawWhenNoHeadersAndLeadingCrlf(): void
    {
        $this->assertSame(
            "\r\nBODY",
            new ParsedResponse("\r\nBODY")->body(),
            'Must preserve raw when no headers are present'
        );
    }

    #[Test]
    public function returnsBodyForLastHeaderBlockWhenDuplicate(): void
    {
        $raw =
            "HTTP/1.1 301 Moved\r\nH: a\r\n\r\nOLD" .
            "HTTP/1.1 200 OK\r\nH: b\r\n\r\nNEW";
        $this->assertSame(
            'NEW',
            new ParsedResponse($raw)->body(),
            'Must return body after the last header block'
        );
    }

    #[Test]
    public function returnsRawWhenNoHeaders(): void
    {
        $this->assertSame(
            'RAW',
            new ParsedResponse('RAW')->body(),
            'Must return raw content when no headers found'
        );
    }

    #[Test]
    public function returnsBodyAfterLastDuplicateHeaderBlock(): void
    {
        // Два одинаковых блока; тело должно быть после последнего
        $raw = "HTTP/1.1 200 OK\r\nX: a\r\n\r\nFirst"
            . "\r\nHTTP/1.1 200 OK\r\nX: a\r\n\r\nSecond";
        $this->assertSame(
            'Second',
            new ParsedResponse($raw)->body(),
            'Must use the last matching header block when duplicates occur'
        );
    }

    #[Test]
    public function preservesRawWhenNoHeadersEvenIfStartsWithNewlines(): void
    {
        $raw = "\r\n\nRAW";
        $this->assertSame(
            $raw,
            new ParsedResponse($raw)->body(),
            'Must not trim raw when no headers are present'
        );
    }
}
