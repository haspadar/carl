// review: noop

<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

use Carl\Exception;

use function is_array;

use const JSON_THROW_ON_ERROR;

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
    public function json(): array
    {
        $data = json_decode($this->origin->body(), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            throw new Exception('JSON root is not an object/array');
        }
        /** @var array<string|int,mixed> $data */
        return $data;
    }
}
