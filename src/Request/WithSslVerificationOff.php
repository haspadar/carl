<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 *  ⚠️ Disables all SSL verification (peer, host, OCSP stapling).
 *  Use only in trusted environments (e.g. debugging, local dev).
 */
final readonly class WithSslVerificationOff implements Request
{
    public function __construct(private Request $origin)
    {
    }

    #[Override]
    /**
     * In case the origin enables status verification (OCSP stapling),
     * this decorator also ensures it's off.
     */
    public function options(): array
    {
        $options = $this->origin->options();
        $options[CURLOPT_SSL_VERIFYPEER] = false;
        $options[CURLOPT_SSL_VERIFYHOST] = 0;

        if (defined('CURLOPT_SSL_VERIFYSTATUS')) {
            $options[CURLOPT_SSL_VERIFYSTATUS] = false;
        }

        return $options;
    }
}
