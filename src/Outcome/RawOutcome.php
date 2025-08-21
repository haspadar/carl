<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome;

use Carl\Reaction\Reaction;
use Carl\Request\Request;
use Carl\Response\Response;

final readonly class RawOutcome implements Outcome
{
    public function __construct(
        private Outcome $origin,
        private string $raw
    ) {
    }

    #[\Override]
    public function response(): Response
    {
        return $this->origin->response();
    }

    public function raw(): string
    {
        return $this->raw;
    }

    #[\Override]
    public function request(): Request
    {
        return $this->origin->request();
    }

    #[\Override]
    public function react(Reaction $reaction): void
    {
        $this->origin->react($reaction);
    }
}
