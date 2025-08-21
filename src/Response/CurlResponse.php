<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

use Override;

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
