<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\RedirectUrl;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RedirectUrlTest extends TestCase
{
    #[Test]
    public function returnsRedirectUrl(): void
    {
        $url = 'https://example.com/final';

        $response = new RedirectUrl(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo(['redirect_url' => $url]),
            ),
        );

        $this->assertSame(
            $url,
            $response->value(),
            'RedirectUrl must return the last redirect URL from CurlInfo'
        );
    }

    #[Test]
    public function returnsEmptyStringIfMissing(): void
    {
        $response = new RedirectUrl(
            new CurlResponse(
                'irrelevant',
                [],
                new CurlInfo([]),
            ),
        );

        $this->assertSame(
            '',
            $response->value(),
            'RedirectUrl must return empty string when redirect_url is missing'
        );
    }
}
