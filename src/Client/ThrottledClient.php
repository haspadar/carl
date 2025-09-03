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

/**
 * Client decorator that throttles execution of request batches.
 *
 * Applies a single delay after each outcomes() call (i.e., per batch), not between
 * individual requests within the batch. To throttle between chunks, compose as:
 * new ChunkedClient(new ThrottledClient($origin, $delaySeconds), $chunkSize).
 *
 * Notes:
 * - When delaySeconds = 0.0, no pause is applied.
 * - Empty batches do not sleep.
 * - outcome() executes without delay.
 *
 * Uses Delay abstraction for sleeping (NativeDelay by default).
 */
final readonly class ThrottledClient implements Client
{
    /**
     * @param float $delaySeconds Non-negative seconds to sleep after each batch (outcomes() call)
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
    public function outcomes(iterable $requests, Reaction $reaction = new VoidReaction()): array
    {
        if ($requests === []) {
            return [];
        }

        $outcomes = $this->origin->outcomes($requests, $reaction);

        if ($outcomes !== [] && $this->delaySeconds > 0.0) {
            $microseconds = max(1, (int)ceil($this->delaySeconds * 1_000_000.0));
            $this->delay->sleep($microseconds);
        }

        return $outcomes;
    }
}
