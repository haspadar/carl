<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Request;

use Carl\Request\GetRequest;
use Carl\Request\WithHttpVersion;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithHttpVersionTest extends TestCase
{
    #[Test]
    public function throwsOnUnsupportedVersion(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Unsupported HTTP version\b/');
        new WithHttpVersion(new GetRequest('http://example.test'), -1)->options();
    }

    #[Test]
    public function setsHttpVersionOption(): void
    {
        $opts = new WithHttpVersion(
            new GetRequest('http://example.test'),
            CURL_HTTP_VERSION_1_1
        )->options();

        $this->assertSame(
            CURL_HTTP_VERSION_1_1,
            $opts[CURLOPT_HTTP_VERSION] ?? null
        );
    }

}
