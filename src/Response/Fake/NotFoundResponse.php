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
 * Fake HTTP response representing a "Not Found" error (404).
 *
 * Useful in tests when simulating missing resources
 * without making real HTTP requests.
 *
 * Example:
 * $response = new NotFoundResponse();
 * echo $response->info()->value(CURLINFO_RESPONSE_CODE); // 404
 */
final readonly class NotFoundResponse implements Response
{
    public function __construct(private string $message = 'Not Found')
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
            CURLINFO_RESPONSE_CODE => 404,
        ]);
    }
}
