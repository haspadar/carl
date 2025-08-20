<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

final readonly class StatusCode
{
    public function __construct(private Response $response)
    {
    }

    public function value(): int
    {
        return (int) $this->response->info()->value(CURLINFO_RESPONSE_CODE);
    }

    public function isSuccessful(): bool
    {
        return $this->isInRange(200, 300);
    }

    public function isInRange(int $min, int $max): bool
    {
        $code = $this->value();

        return $code >= $min && $code < $max;
    }
}
