<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response\Fake;

use Carl\Response\CurlInfo;
use Carl\Response\Response;

use const CURLINFO_RESPONSE_CODE;

use Override;

/**
 * @codeCoverageIgnore
 *
 * Fake HTTP response that always represents a server error (500).
 *
 * Useful in tests to simulate failing HTTP calls
 * without requiring a real server-side failure.
 *
 * Example:
 * $response = new ServerErrorResponse("Something went wrong");
 * echo $response->info()->value(CURLINFO_RESPONSE_CODE); // 500
 */
final readonly class ServerErrorResponse implements Response
{
    public function __construct(private string $message = 'Internal Server Error')
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
            CURLINFO_RESPONSE_CODE => 500,
        ]);
    }
}
