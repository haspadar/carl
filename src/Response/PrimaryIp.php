<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

/**
 * Value object for extracting the primary IP address from a Response.
 *
 * Wraps a Response and exposes the resolved IP address of the connection
 * as reported by curl_getinfo() under the 'primary_ip' key.
 *
 * Example:
 * $ip = (new PrimaryIp($response))->value(); // e.g. "93.184.216.34"
 */
final readonly class PrimaryIp
{
    public function __construct(private Response $response)
    {
    }

    public function value(): string
    {
        return $this->response->info()->value('primary_ip', '0.0.0.0');
    }
}
