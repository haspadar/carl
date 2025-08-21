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

    /**
     * @throws JsonException
     */
    public function assertReflectedMethod(Response $response, string $expected): void
    {
        $this->assertMethod($this->reflected($response->body()), $expected);
    }

    public function assertReflectedPath(Response $response, string $expected): void
    {
        Assert::assertSame(
            $expected,
            $this->reflected($response->body())['path'] ?? '',
            "Expected path: $expected",
        );
    }

    public function assertMethod(array $reflected, string $expected): void
    {
        Assert::assertSame(
            $expected,
            $reflected['method'] ?? '',
            "Expected method: $expected",
        );
    }

    public function assertReflectedHeader(Response $response, string $name, string $expected): void
    {
        $reflected = $this->reflected($response->body());
        $this->assertHasHeader($reflected, $name);
        $this->assertHeader(
            $reflected,
            $name,
            $expected,
        );
    }

    public function assertReflectedHeaderContains(Response $response, string $name, string $substring): void
    {
        $reflected = $this->reflected($response->body());
        $this->assertHasHeader($reflected, $name);

        foreach ($reflected['headers'] as $key => $value) {
            if (strcasecmp($key, $name) === 0) {
                Assert::assertStringContainsStringIgnoringCase(
                    $substring,
                    $value,
                    "Expected header $name to contain: $substring (actual: $value)",
                );
                return;
            }
        }

        Assert::fail("Expected header '$name' to be present (case-insensitive)");
    }

    public function assertHeader(array $reflected, string $name, string $expected): void
    {
        foreach ($reflected['headers'] as $key => $value) {
            if (strcasecmp($key, $name) === 0) {
                Assert::assertSame(
                    $expected,
                    $value,
                    "Expected header $name: $expected, got: $value"
                );
                return;
            }
        }

        Assert::fail("Expected header $name to be present (case-insensitive)");
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
            "Expected body: $expected",
        );
    }

    public function assertContentType(array $reflected, string $expected): void
    {
        $this->assertHeader($reflected, 'content-type', $expected);
    }

    public function assertHasHeader(array $reflected, string $name): void
    {
        Assert::assertNotEmpty(
            array_filter(
                array_keys($reflected['headers'] ?? []),
                fn (string $key): bool => strcasecmp($key, $name) === 0,
            ),
            "Expected header '$name' to be present (case-insensitive)",
        );
    }
}
