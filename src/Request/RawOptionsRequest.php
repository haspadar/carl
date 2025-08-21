<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Request with raw cURL options passed directly.
 *
 * Useful for testing or fine-tuned requests without higher-level wrappers.
 */
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
