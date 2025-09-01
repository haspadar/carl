<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

/**
 * Immutable wrapper around the array returned by curl_getinfo().
 *
 * Provides safe access to cURL transfer metadata such as 'url',
 * 'content_type', 'http_code', etc. Keys may be accessed by their
 * string names as defined by curl_getinfo().
 *
 * Example:
 * $info = new CurlInfo(curl_getinfo($handle));
 * $code = $info->value('http_code'); // e.g. 200
 */
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
