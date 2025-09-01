<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response\Fake;

use Carl\Response\CurlInfo;
use Carl\Response\Response;
use Override;

/**
 * @codeCoverageIgnore
 *
 * Fake HTTP response representing an unauthorized access (HTTP 401).
 *
 * Useful in tests to simulate cases where authentication
 * is required or the provided credentials are invalid.
 *
 * Example:
 * $response = new UnauthorizedResponse();
 * echo $response->info()->value('http_code'); // 401
 */
final readonly class UnauthorizedResponse implements Response
{
    public function __construct(private string $message = 'Unauthorized')
    {
    }

    #[Override]
    public function body(): string
    {
        return $this->message;
    }

    #[Override]
    public function headers(): array
    {
        return ['Content-Type' => 'text/plain'];
    }

    #[Override]
    public function info(): CurlInfo
    {
        return new CurlInfo([
            'http_code' => 401,
        ]);
    }
}
