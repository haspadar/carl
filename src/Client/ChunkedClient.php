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
use Override;

/**
 * Client decorator for batching requests.
 *
 * Splits an array of requests into smaller chunks and delegates them
 * to the underlying client. Useful when the target system or network
 * cannot handle too many concurrent requests at once.
 *
 * Example:
 * ```php
 * $client = new ChunkedClient(new CurlClient(), 10);
 * $outcomes = $client->outcomes($requests);
 * ```
 */
final readonly class ChunkedClient implements Client
{
    /**
     * @param int<1, max> $chunkSize Chunk size, must be a positive integer (>=1)
     */
    public function __construct(
        private Client $origin,
        private int $chunkSize
    ) {
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
        foreach (array_chunk($requests, $this->chunkSize) as $chunk) {
            $result = [...$result, ...$this->origin->outcomes($chunk, $reaction)];
        }
        return $result;
    }
}
