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
 * Implementations should preserve request order and produce the same number
 * of outcomes as requests. The given Reaction is applied to each produced outcome.
 */
interface Client
{
    /**
     * Execute a batch of requests.
     *
     * Implementations should preserve the order of $requests and return one
     * Outcome per Request. The $reaction is invoked for every produced Outcome.
     *
     * @param iterable<Request>            $requests Ordered requests to execute
     * @param Reaction                     $reaction Reaction to apply to each outcome
     * @return list<Outcome>                         Outcomes in the same order as $requests
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
