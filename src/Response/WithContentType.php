<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

use Override;

final readonly class WithContentType implements Response
{
    public function __construct(
        private Response $origin,
        private string $type
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
        return [
            ...$this->origin->headers(),
            'Content-Type' => $this->type,
        ];
    }

    #[Override]
    public function info(): CurlInfo
    {
        return $this->origin->info();
    }
}
