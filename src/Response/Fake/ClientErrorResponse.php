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
 * Fake HTTP response representing a client error (HTTP 400).
 */
final readonly class ClientErrorResponse implements Response
{
    public function __construct(private string $message = 'Bad Request')
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
        return ['Content-Type' => 'text/plain; charset=utf-8'];
    }

    #[Override]
    public function info(): CurlInfo
    {
        return new CurlInfo(['http_code' => 400]);
    }
}