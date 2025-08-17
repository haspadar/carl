// review: noop

<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

final readonly class RawOptionsRequest implements Request
{
    /**
     * @param array<int, mixed> $options curl options in CURLOPT_* => value format
     */
    public function __construct(private array $options)
    {
    }

    /**
     * @return array<int, mixed> curl options to apply
     */
    #[Override]
    public function options(): array
    {
        return $this->options;
    }
}
