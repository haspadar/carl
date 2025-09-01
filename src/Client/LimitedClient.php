<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Client;

use Carl\Exception;
use Carl\Outcome\Outcome;
use Carl\Reaction\Reaction;
use Carl\Reaction\VoidReaction;
use Carl\Request\Request;
use Override;

/**
 * Client decorator that enforces a maximum number of requests.
 *
 * Ignores any requests beyond the configured limit.
 */
final readonly class LimitedClient implements Client
{
    /**
     * @param int $limit Maximum number of requests to execute
     */
    public function __construct(
        private Client $origin,
        private int $limit,
    ) {
    }

    #[Override]
    public function outcome(Request $request, Reaction $reaction = new VoidReaction()): Outcome
    {
        return $this->origin->outcome($request, $reaction);
    }

    #[Override]
    public function outcomes(iterable $requests, Reaction $reaction = new VoidReaction()): array
    {
        if ($this->limit < 1) {
            throw new Exception("Limit must be >= 1, got $this->limit");
        }

        $limited = (function () use ($requests) {
            $count = 0;
            foreach ($requests as $request) {
                if ($count++ >= $this->limit) {
                    break;
                }
                yield $request;
            }
        })();

        return $this->origin->outcomes($limited, $reaction);
    }
}
