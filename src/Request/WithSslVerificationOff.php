<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * WARNING: Disabling verification weakens security; limit to trusted environments.
 */
final readonly class WithSslVerificationOff implements Request
{
    public function __construct(private Request $origin) {}

    #[Override]
    public function options(): array
    {
        $options = $this->origin->options();
        $options[CURLOPT_SSL_VERIFYPEER] = false;
        $options[CURLOPT_SSL_VERIFYHOST] = 0;

        return $options;
    }
}
