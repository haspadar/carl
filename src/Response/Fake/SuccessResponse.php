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
 * Fake HTTP response that always represents success.
 *
 * Useful in tests where a simple successful response
 * is required without making a real HTTP call.
 *
 * Example:
 * $response = new SuccessResponse("Hello");
 * echo $response->body(); // "Hello"
 */
final readonly class SuccessResponse implements Response
{
    public function __construct(private string $message = 'OK')
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
            CURLINFO_RESPONSE_CODE => 200,
        ]);
    }
}
