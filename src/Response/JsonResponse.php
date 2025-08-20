<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

use function is_array;

use JsonException;
use Override;

final readonly class JsonResponse implements Response
{
    public function __construct(
        private Response $origin,
    ) {
    }

    #[Override]
    public function body(): string
    {
        return $this->origin->body();
    }

    /** @return array<string,string> */
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

    /**
     * @throws JsonException
     * @return array<string|int,mixed>
     */
    public function decoded(): array
    {
        $data = json_decode($this->origin->body(), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            throw new JsonException('JSON root is not an object/array');
        }

        return $data;
    }
}
