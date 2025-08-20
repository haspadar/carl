<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Support;

use Carl\Response\Response;
use PHPUnit\Framework\Assert;

trait AssertsHttpResponse
{
    protected function assertStatusCode(Response $response, int $expected, string $message = ''): void
    {
        $actual = (int) $response->info()->value('http_code');
        Assert::assertSame($expected, $actual, $message !== '' ? $message : "Expected status code $expected, got $actual");
    }
}
