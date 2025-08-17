<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Client;

use Carl\Outcome\Outcome;
use Carl\Reaction\Reaction;
use Carl\Reaction\VoidReaction;
use Carl\Request\Request;
use InvalidArgumentException;
use Override;

use function round;

final readonly class ThrottledClient implements Client
{
    /**
     * @param float $delaySeconds Non-negative seconds to sleep between requests
     */
    public function __construct(
        private Client $origin,
        private float $delaySeconds,
    ) {
        if ($delaySeconds < 0.0) {
            throw new InvalidArgumentException('delaySeconds must be >= 0.0');
        }
    }

    #[Override]
    public function outcome(Request $request, Reaction $reaction = new VoidReaction()): Outcome
    {
        return $this->origin->outcome($request, $reaction);
    }

    #[Override]
    public function outcomes(array $requests, Reaction $reaction = new VoidReaction()): array
    {
        $result = [];
        foreach ($requests as $request) {
            $result[] = $this->origin->outcome($request, $reaction);
            if ($this->delaySeconds > 0.0) {
                $microseconds = (int)round($this->delaySeconds * 1_000_000.0);
                usleep($microseconds);
            }
        }

        return $result;
    }
}
