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
 * Fake HTTP response that always represents a redirect (302).
 * Decorates an origin response, overriding Location header and http_code.
 */
final readonly class RedirectResponse implements Response
{
    public function __construct(
        private Response $origin,
        private string $location,
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
        return new WithHeaderOverride(
            $this->origin,
            ['Location' => $this->location]
        )->headers();
    }

    #[Override]
    public function info(): CurlInfo
    {
        return new WithInfoOverride(
            $this->origin,
            ['http_code' => 302, 'redirect_url' => $this->location]
        )->info();
    }
}
