<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Request;

use Carl\Request\PostRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PostRequestTest extends TestCase
{
    #[Test]
    public function setsPostAndReturnTransferOptions(): void
    {
        $request = new PostRequest('http://localhost');
        $options = $request->options();

        $this->assertSame('http://localhost', $options[CURLOPT_URL] ?? null);
        $this->assertTrue($options[CURLOPT_POST] ?? false);
        $this->assertTrue($options[CURLOPT_RETURNTRANSFER] ?? false);
    }

    #[Test]
    public function doesNotSetPostFields(): void
    {
        $request = new PostRequest('http://localhost');
        $options = $request->options();

        $this->assertArrayNotHasKey(CURLOPT_POSTFIELDS, $options);
    }
}
