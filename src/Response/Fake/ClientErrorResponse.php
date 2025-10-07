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
 * Decorates any Response and overrides http_code lazily.
 */
final readonly class ClientErrorResponse implements Response
{
    public function __construct(private Response $origin = new FixedResponse(400, 'Bad Request'))
    {
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
        return $this->origin->info();
    }
}
