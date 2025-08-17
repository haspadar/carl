<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

final readonly class CurlInfo
{
    /** @param array<string|int, mixed> $info */
    public function __construct(private array $info)
    {
    }

    public function value(string|int $key, string $default = ''): string
    {
        if ($this->hasKey($key) && is_scalar($this->info[$key])) {
            return (string)$this->info[$key];
        }

        return $default;
    }

    public function hasKey(string|int $key): bool
    {
        return array_key_exists($key, $this->info);
    }

    /** @return array<string|int, mixed> */
    public function all(): array
    {
        return $this->info;
    }
}
