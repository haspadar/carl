<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlPayload;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CurlPayloadTest extends TestCase
{
    #[Test]
    public function returnsLastHeaderBlockWhenMultiplePresent(): void
    {
        $raw = "HTTP/1.1 301 Moved\r\nH1: a\r\n\r\n" .
            "HTTP/1.1 200 OK\r\nH2: b\r\n\r\nBODY";
        $this->assertSame(
            "HTTP/1.1 200 OK\r\nH2: b",
            new CurlPayload($raw)->lastHeaderBlock(),
            'Must extract the last header block'
        );
    }

    #[Test]
    public function returnsHeadersWhenParsed(): void
    {
        $raw = "HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\nX-Id: 7\r\n\r\nok";
        $this->assertSame(
            ['Content-Type' => 'text/plain', 'X-Id' => '7'],
            new CurlPayload($raw)->headers(),
            'Must parse header lines into an associative array'
        );
    }

    #[Test]
    public function returnsBodyWhenHeadersPresent(): void
    {
        $raw = "HTTP/1.1 200 OK\r\nA: 1\r\n\r\nHello";
        $this->assertSame(
            'Hello',
            new CurlPayload($raw)->body(),
            'Must return body after the last header block'
        );
    }

    #[Test]
    public function returnsRawWhenNoHeadersAndLeadingCrlf(): void
    {
        $this->assertSame(
            "\r\nBODY",
            new CurlPayload("\r\nBODY")->body(),
            'Must preserve raw when no headers are present'
        );
    }

    #[Test]
    public function returnsBodyForLastHeaderBlockWhenDuplicate(): void
    {
        $raw =
            "HTTP/1.1 301 Moved\r\nH: a\r\n\r\nOLD\r\n\r\n" .
            "HTTP/1.1 200 OK\r\nH: b\r\n\r\nNEW";
        $this->assertSame(
            'NEW',
            new CurlPayload($raw)->body(),
            'Must return body after the last header block'
        );
    }

    #[Test]
    public function returnsRawWhenNoHeaders(): void
    {
        $this->assertSame(
            'RAW',
            new CurlPayload('RAW')->body(),
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
            new CurlPayload($raw)->body(),
            'Must use the last matching header block when duplicates occur'
        );
    }

    #[Test]
    public function preservesRawWhenNoHeadersEvenIfStartsWithNewlines(): void
    {
        $raw = "\r\n\nRAW";
        $this->assertSame(
            $raw,
            new CurlPayload($raw)->body(),
            'Must not trim raw when no headers are present'
        );
    }

    #[Test]
    public function bodyExtractionWorksWhenBodyContainsCrlfCrlf(): void
    {
        $payload = implode('', [
            "HTTP/1.1 200 OK\r\n",
            "Content-Type: text/plain\r\n",
            "\r\n",
            "line1\r\n",
            "line2\r\n\r\n",
            "line3",
        ]);

        $curlPayload = new CurlPayload($payload);

        $this->assertSame(
            "line1\r\nline2\r\n\r\nline3",
            $curlPayload->body(),
            'Must not truncate body if it contains CRLFCRLF'
        );
    }

    #[Test]
    public function mergesDuplicateHeadersIntoSingleValue(): void
    {
        $raw = implode('', [
            "HTTP/1.1 200 OK\r\n",
            "X-Foo: A\r\n",
            "X-Foo: B\r\n",
            "\r\n",
            "BODY"
        ]);

        $headers = new CurlPayload($raw)->headers();

        $this->assertSame(
            ['X-Foo' => 'A, B'],
            $headers,
            'Must merge duplicate headers into a single comma-separated value'
        );
    }

    #[Test]
    public function returnsRawUnchanged(): void
    {
        $raw = "HTTP/1.1 200 OK\r\nX: x\r\n\r\nBody";
        $payload = new CurlPayload($raw);

        $this->assertSame(
            $raw,
            $payload->raw(),
            'raw() must return the original payload without changes'
        );
    }

    #[Test]
    public function returnsRawWhenMatchSucceedsButEmptyBlockList(): void
    {
        // Пустой raw, но preg_match_all всё равно отрабатывает (0 совпадений)
        $raw = '   ';

        $this->assertSame(
            $raw,
            new CurlPayload($raw)->body(),
            'Must return raw when preg_match_all matches but no valid blocks'
        );
    }

    #[Test]
    public function mergesHeadersCaseInsensitively(): void
    {
        $raw = "HTTP/1.1 200 OK\r\nX-Foo: a\r\nx-foo: b\r\n\r\nbody";
        $payload = new CurlPayload($raw);

        $this->assertSame(
            ['X-Foo' => 'a, b'],
            $payload->headers(),
            'Duplicate headers with different casing must merge'
        );
    }
}
