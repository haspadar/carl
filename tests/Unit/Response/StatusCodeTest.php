<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\StatusCode;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StatusCodeTest extends TestCase
{
    #[Test]
    public function returnsStatusCode(): void
    {
        $status = new StatusCode(
            new CurlResponse('...', [], new CurlInfo([
                CURLINFO_RESPONSE_CODE => 201,
            ])),
        );

        $this->assertSame(201, $status->value(), 'Should return HTTP status code');
    }

    #[Test]
    public function isSuccessfulIf2xx(): void
    {
        $status = new StatusCode(
            new CurlResponse('...', [], new CurlInfo([
                CURLINFO_RESPONSE_CODE => 204,
            ])),
        );

        $this->assertTrue($status->isSuccessful(), '2xx should be successful');
    }

    #[Test]
    public function isNotSuccessfulIfNot2xx(): void
    {
        $status = new StatusCode(
            new CurlResponse('...', [], new CurlInfo([
                CURLINFO_RESPONSE_CODE => 404,
            ])),
        );

        $this->assertFalse($status->isSuccessful(), 'Non-2xx should not be successful');
    }

    #[Test]
    public function isInRangeCorrectly(): void
    {
        $status = new StatusCode(
            new CurlResponse('...', [], new CurlInfo([
                CURLINFO_RESPONSE_CODE => 301,
            ])),
        );

        $this->assertTrue($status->isInRange(300, 400), '301 should be in 3xx range');
        $this->assertFalse($status->isInRange(400, 500), '301 should not be in 4xx range');
    }
}
