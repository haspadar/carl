<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Request;

use Carl\Request\RawOptionsRequest;
use Carl\Request\WithProxy;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithProxyTest extends TestCase
{
    #[Test]
    public function addsProxyOption(): void
    {
        $origin = new RawOptionsRequest([]);
        $decorated = new WithProxy(
            $origin,
            'http://proxy.local:3128'
        );

        $options = $decorated->options();

        $this->assertSame('http://proxy.local:3128', $options[CURLOPT_PROXY], 'Should set proxy URL');
    }
}
