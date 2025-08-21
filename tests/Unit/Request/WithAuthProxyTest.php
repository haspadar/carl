<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Request;

use Carl\Request\RawOptionsRequest;
use Carl\Request\WithAuthProxy;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithAuthProxyTest extends TestCase
{
    #[Test]
    public function addsProxyOptions(): void
    {
        $origin = new RawOptionsRequest([]);

        $decorated = new WithAuthProxy(
            $origin,
            'http://proxy.local:8080',
            'john',
            'secret'
        );

        $options = $decorated->options();

        $this->assertSame('http://proxy.local:8080', $options[CURLOPT_PROXY], 'Should set proxy URL');
        $this->assertSame('john:secret', $options[CURLOPT_PROXYUSERPWD], 'Should set proxy auth');
    }
}
