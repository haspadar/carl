<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\RedirectCount;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RedirectCountTest extends TestCase
{
    #[Test]
    public function returnsRedirectCount(): void
    {
        $response = new RedirectCount(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo(['redirect_count' => 2]),
            ),
        );

        $this->assertSame(
            2,
            $response->count(),
            'RedirectCount must return the redirect_count value from CurlInfo'
        );
    }

    #[Test]
    public function returnsZeroIfMissing(): void
    {
        $response = new \Carl\Response\RedirectCount(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo([]),
            ),
        );

        $this->assertSame(
            0,
            $response->count(),
            'RedirectCount must return 0 when redirect_count is missing'
        );
    }
}
