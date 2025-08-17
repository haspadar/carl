<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl;

use function is_array;
use function is_string;

final readonly class CurlVersionOf
{
    /** @param array<string,mixed>|false $version */
    public function __construct(private array|false $version)
    {
    }

    public function value(): string
    {
        if (is_array($this->version) && isset($this->version['version']) && is_string($this->version['version'])) {
            return $this->version['version'];
        }
        return 'unknown';
    }
}
