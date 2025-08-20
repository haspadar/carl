<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome\Fake;

use Carl\Outcome\Outcome;
use Carl\Request\Request;
use InvalidArgumentException;
use Override;

/**
 * Cycles through a predefined list of outcomes.
 *
 * Useful in tests when you want predictable,
 * repeatable sequences of outcomes.
 *
 * Example:
 * new Cycle([$ok, $fail])->at(2, $req); // returns $ok
 *
 * @codeCoverageIgnore
 */
final readonly class Cycle implements FakeOutcomes
{
    /**
     * @param Outcome[] $outcomes
     */
    public function __construct(private array $outcomes)
    {
    }

    /**
     * @throws InvalidArgumentException
     * @param int $index Zero-based (non-negative) index of the request
     *                   in the batch, as provided by FakeClient.
     */
    #[Override]
    public function at(int $index, Request $request): Outcome
    {
        if ($index < 0) {
            throw new InvalidArgumentException('Index must be non-negative');
        }

        if ($this->outcomes === []) {
            throw new InvalidArgumentException('At least one outcome must be provided');
        }

        return $this->outcomes[$index % count($this->outcomes)];
    }
}
