<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

use Override;

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

    /**
     * Adds both 'CURLINFO_RESPONSE_CODE' (curl constant) and 'http_code' (legacy array key)
     * to preserve compatibility with all consumers of CurlInfo.
     */
    #[Override]
    public function info(): CurlInfo
    {
        return new CurlInfo([
            ...$this->origin->info()->all(),
            CURLINFO_RESPONSE_CODE => $this->code,
            'http_code' => $this->code,
        ]);
    }
}
