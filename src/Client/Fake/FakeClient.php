<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Client\Fake;

use Carl\Client\Client;
use Carl\Outcome\Fake\FakeOutcomes;
use Carl\Outcome\Outcome;
use Carl\Reaction\Reaction;
use Carl\Reaction\VoidReaction;
use Carl\Request\Request;
use Override;

/**
 * Fake implementation of Client.
 *
 * Useful in unit tests for providing predefined outcomes
 * without performing real HTTP requests.
 *
 * Example:
 * $outcomes = (new FakeClient(
 *     new Cycle([
 *         new AlwaysSuccessful(),
 *         new AlwaysFails()
 *     ])
 * ))->outcomes([new GetRequest("http://a"), new GetRequest("http://b")]);
 */
final readonly class FakeClient implements Client
{
    public function __construct(private FakeOutcomes $fakeOutcomes)
    {
    }

    #[Override]
    public function outcome(Request $request, Reaction $reaction = new VoidReaction()): Outcome
    {
        return $this->outcomes([$request], $reaction)[0];
    }

    #[Override]
    public function outcomes(array $requests, Reaction $reaction = new VoidReaction()): array
    {
        $result = [];

        foreach ($requests as $i => $request) {
            $outcome = $this->fakeOutcomes->at($i, $request);
            $outcome->react($reaction);
            $result[] = $outcome;
        }

        return $result;
    }
}
