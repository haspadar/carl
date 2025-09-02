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
 * Client decorator for batching requests.
 *
 * Splits an array of requests into smaller chunks and delegates them
 * to the underlying client. Useful when the target system or network
 * cannot handle too many concurrent requests at once.
 *
 * Example:
 *
 * $client = new ChunkedClient(new CurlClient(), 10);
 * $outcomes = $client->outcomes($requests);
 */
final readonly class ChunkedClient implements Client
{
    /**
     * @param int<1, max> $size Chunk size, must be a positive integer (>=1)
     */
    public function __construct(
        private Client $origin,
        private int $size,
    ) {
    }

    #[Override]
    public function outcome(Request $request, Reaction $reaction = new VoidReaction()): Outcome
    {
        return $this->origin->outcome($request, $reaction);
    }

    /**
     * Executes requests in fixed-size chunks.
     *
     * Each chunk is delegated to the underlying client, and outcomes are
     * collected from all chunks in chunk submission order. If the underlying
     * client preserves request order within each chunk, then the overall outcome
     * order will correspond to input order.
     *
     * Note: If the inner client returns outcomes in completion order (e.g. CurlClient),
     * the final outcome order may differ from the input.
     *
     * @throws Exception
     * @param Reaction $reaction Reaction to apply to each outcome
     * @return list<Outcome>              Combined outcomes from all chunks
     * @param iterable<Request> $requests Requests to send in batches
     */
    #[Override]
    public function outcomes(iterable $requests, Reaction $reaction = new VoidReaction()): array
    {
        $result = [];
        $buffer = [];

        foreach ($requests as $request) {
            $buffer[] = $request;

            if (count($buffer) === $this->size) {
                $outcomes = $this->origin->outcomes($buffer, $reaction);
                array_push($result, ...$outcomes);
                $buffer = [];
            }
        }

        if ($buffer !== []) {
            $outcomes = $this->origin->outcomes($buffer, $reaction);
            array_push($result, ...$outcomes);
        }

        return $result;
    }
}
