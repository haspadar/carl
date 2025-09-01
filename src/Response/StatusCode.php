<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

/**
 * Value object for extracting the HTTP status code from a Response.
 *
 * Wraps a Response and exposes helpers to check its status code,
 * using the 'http_code' key from curl_getinfo().
 *
 * Example:
 * $status = new StatusCode($response);
 * if ($status->isSuccessful()) {
 *     // 2xx range
 * }
 */
final readonly class StatusCode
{
    public function __construct(private Response $response)
    {
    }

    public function value(): int
    {
        return (int) $this->response->info()->value('http_code');
    }

    public function isSuccessful(): bool
    {
        return $this->isInRange(200, 300);
    }

    public function isInRange(int $min, int $max): bool
    {
        $code = $this->value();

        return $code >= $min && $code < $max;
    }
}
