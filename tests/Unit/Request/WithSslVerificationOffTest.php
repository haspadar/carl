<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Unit\Request;

use Carl\Request\RawOptionsRequest;
use Carl\Request\WithSslVerificationOff;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithSslVerificationOffTest extends TestCase
{
    #[Test]
    public function setsAllSslOptions(): void
    {
        $request = new WithSslVerificationOff(
            new RawOptionsRequest([])
        );

        $options = $request->options();

        $this->assertFalse($options[CURLOPT_SSL_VERIFYPEER]);
        $this->assertSame(0, $options[CURLOPT_SSL_VERIFYHOST]);
        $this->assertFalse($options[CURLOPT_SSL_VERIFYSTATUS]);
    }
}
