<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

use Carl\Exception;
use Override;

/**
 * Response decorator that overrides the HTTP status code.
 *
 * Ensures that CurlInfo contains the provided code under the 'http_code' key,
 * which is the same format as produced by curl_getinfo().
 *
 * Example:
 * $response = new WithStatusCode($origin, 503);
 * $status   = $response->info()->value('http_code'); // 503
 */
final readonly class WithStatusCode implements Response
{
    public function __construct(
        private Response $origin,
        private int $code,
    ) {
    }

    #[Override]
    public function body(): string
    {
        return $this->origin->body();
    }

    #[Override]
    public function headers(): array
    {
        return $this->origin->headers();
    }

    #[Override]
    public function info(): CurlInfo
    {
        if ($this->code < 100 || $this->code > 599) {
            throw new Exception("Invalid HTTP status code: $this->code");
        }

        return new CurlInfo(array_replace(
            $this->origin->info()->all(),
            ['http_code' => $this->code],
        ));
    }
}
