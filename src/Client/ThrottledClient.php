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
use Carl\Time\Delay;
use Carl\Time\NativeDelay;
use Override;

use function round;

/**
 * Client decorator that throttles request execution.
 *
 * Adds a delay between consecutive requests in outcomes().
 * Delay is specified in seconds (may be fractional).
 *
 * - When delaySeconds = 0.0 no pause is applied.
 * - For N requests, the delay is applied (N-1) times.
 * - outcome() executes without delay.
 *
 * Uses Delay abstraction for sleeping (NativeDelay by default).
 */
final readonly class ThrottledClient implements Client
{
    /**
     * @param float $delaySeconds Non-negative seconds to sleep between requests
     */
    public function __construct(
        private Client $origin,
        private float $delaySeconds,
        private Delay $delay = new NativeDelay(),
    ) {
        if ($delaySeconds < 0.0 || !is_finite($delaySeconds)) {
            throw new Exception('delaySeconds must be finite and >= 0.0');
        }
    }

    #[Override]
    public function outcome(Request $request, Reaction $reaction = new VoidReaction()): Outcome
    {
        return $this->origin->outcome($request, $reaction);
    }

    #[Override]
    /**
     * @param list<Request> $requests
     * @return list<Outcome>
     */
    public function outcomes(array $requests, Reaction $reaction = new VoidReaction()): array
    {
        $result = [];
        $lastKey = array_key_last($requests);
        $microseconds = $this->delaySeconds > 0.0 ? (int)round($this->delaySeconds * 1_000_000.0) : 0;
        foreach ($requests as $key => $request) {
            $result[] = $this->origin->outcome($request, $reaction);
            if ($microseconds > 0 && $key !== $lastKey) {
                $this->delay->sleep($microseconds);
            }
        }

        return $result;
    }
}
