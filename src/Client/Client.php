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

/**
 * HTTP client abstraction.
 *
 * Implementations must return one Outcome per Request.
 * The output order is implementation-defined and may not match the input.
 * Reactions are invoked per produced outcome.
 */
interface Client
{
    /**
     * Execute a batch of requests.
     *
     * The number of returned outcomes will match the number of requests.
     * Implementations may return them in any order.
     *
     * @param iterable<Request> $requests Requests to execute
     * @param Reaction          $reaction Reaction to apply to each outcome
     * @return list<Outcome>              Outcomes for the requests
     */
    public function outcomes(iterable $requests, Reaction $reaction = new VoidReaction()): array;

    /**
     * Execute a single request.
     *
     * Equivalent to outcomes([$request], $reaction)[0] in behavior.
     *
     * @param Request   $request  Request to execute
     * @param Reaction  $reaction Reaction to apply to the produced outcome
     * @return Outcome            Outcome of the request
     */
    public function outcome(Request $request, Reaction $reaction = new VoidReaction()): Outcome;
}
