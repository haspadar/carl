<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Support;

use Carl\Response\Response;
use JsonException;
use PHPUnit\Framework\Assert;

trait AssertsReflectedResponse
{
    /**
     * @throws JsonException
     */
    public function reflected(string $raw): array
    {
        return json_decode($raw, true, flags: JSON_THROW_ON_ERROR);
    }

    public function assertReflectedMethod(Response $response, string $expected): void
    {
        $this->assertMethod($this->reflected($response->body()), $expected);
    }

    public function assertReflectedPath(Response $response, string $expected): void
    {
        Assert::assertSame(
            $expected,
            $this->reflected($response->body())['path'] ?? '',
            "Expected path: $expected"
        );
    }

    public function assertMethod(array $reflected, string $expected): void
    {
        Assert::assertSame(
            $expected,
            $reflected['method'] ?? '',
            "Expected method: $expected"
        );
    }

    public function assertHeader(array $reflected, string $name, string $expected): void
    {
        Assert::assertSame(
            $expected,
            $reflected['headers'][$name] ?? '',
            "Expected header $name: $expected"
        );
    }

    public function assertReflectedBody(Response $response, string $expected): void
    {
        $this->assertBody($this->reflected($response->body()), $expected);
    }

    public function assertBody(array $reflected, string $expected): void
    {
        Assert::assertSame(
            $expected,
            $reflected['body'] ?? '',
            "Expected body: $expected"
        );
    }

    public function assertContentType(array $reflected, string $expected): void
    {
        $this->assertHeader($reflected, 'content-type', $expected);
    }

    public function assertHasHeader(array $reflected, string $name): void
    {
        Assert::assertArrayHasKey(
            $name,
            $reflected['headers'],
            "Expected header $name to be present"
        );
    }
}
