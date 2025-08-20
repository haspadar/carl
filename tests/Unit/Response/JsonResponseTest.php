<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\Fake\SuccessResponse;
use Carl\Response\JsonResponse;
use JsonException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class JsonResponseTest extends TestCase
{
    #[Test]
    public function returnsArrayWhenValidJson(): void
    {
        $this->assertSame(
            ['a' => 1, 'b' => ['c' => 2]],
            new JsonResponse(new SuccessResponse('{"a":1,"b":{"c":2}}'))->decoded(),
            'Must decode valid JSON into associative array',
        );
    }

    #[Test]
    public function throwsWhenRootIsNotArrayOrObject(): void
    {
        $this->expectException(JsonException::class);

        new JsonResponse(new SuccessResponse('"str"'))->decoded();
    }

    #[Test]
    public function returnsBodyPassThroughWhenCalled(): void
    {
        $this->assertSame(
            '{"k":42}',
            new JsonResponse(new SuccessResponse('{"k":42}'))->body(),
            'Must proxy body() from origin',
        );
    }

    #[Test]
    public function returnsArrayWhenArrayRoot(): void
    {
        $this->assertSame(
            [1, 2, 3],
            new JsonResponse(new SuccessResponse('[1,2,3]'))->decoded(),
            'Must accept array root',
        );
    }

    #[Test]
    public function throwsJsonExceptionOnMalformedJson(): void
    {
        $this->expectException(JsonException::class);
        new JsonResponse(new SuccessResponse('{"a": '))->decoded();
    }
}
