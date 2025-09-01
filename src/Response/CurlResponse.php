<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

use Override;

/**
 * Immutable HTTP response backed by cURL data.
 *
 * Combines body, parsed headers, and CurlInfo metadata
 * into a single Response implementation.
 *
 * Example:
 * $response = new CurlResponse($body, $headers, $info);
 * echo $response->body();
 * print_r($response->headers());
 * echo $response->info()->value('http_code');
 */
final readonly class CurlResponse implements Response
{
    /**
     * @param array<string,string> $headers
     */
    public function __construct(
        private string $body,
        private array $headers,
        private CurlInfo $curlInfo,
    ) {
    }

    #[Override]
    public function body(): string
    {
        return $this->body;
    }

    #[Override]
    public function headers(): array
    {
        return $this->headers;
    }

    #[Override]
    public function info(): CurlInfo
    {
        return $this->curlInfo;
    }
}
