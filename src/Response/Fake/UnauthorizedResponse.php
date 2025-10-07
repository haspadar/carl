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
 */
final readonly class UnauthorizedResponse implements Response
{
    public function __construct(private Response $origin)
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
        return array_merge(
            $this->origin->headers(),
            ['WWW-Authenticate' => 'Basic realm="FakeServer"']
        );
    }

    #[Override]
    public function info(): CurlInfo
    {
        return new WithInfoOverride($this->origin, ['http_code' => 401])->info();
    }
}
