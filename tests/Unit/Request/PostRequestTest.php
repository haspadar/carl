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
    public function setsPostFieldsWhenBodyProvided(): void
    {
        $request = new PostRequest('http://localhost', 'payload');
        $options = $request->options();

        $this->assertArrayHasKey(
            CURLOPT_POSTFIELDS,
            $options,
            'CURLOPT_POSTFIELDS should be set when body is non-empty'
        );
    }

    #[Test]
    public function omitsPostFieldsWhenBodyEmpty(): void
    {
        $request = new PostRequest('http://localhost', '');
        $options = $request->options();

        $this->assertArrayNotHasKey(
            CURLOPT_POSTFIELDS,
            $options,
            'CURLOPT_POSTFIELDS should be omitted when body is empty'
        );
    }
}
