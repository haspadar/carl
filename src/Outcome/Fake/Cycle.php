// review: noop

<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome\Fake;

use Carl\Outcome\Outcome;
use Carl\Request\Request;
use Exception;
use Override;

final readonly class Cycle implements FakeOutcomes
{
    /**
     * @param Outcome[] $outcomes
     */
    public function __construct(private array $outcomes)
    {
    }

    #[Override]
    public function at(int $index, Request $request): Outcome
    {
        if ($this->outcomes === []) {
            throw new Exception('At least one outcome must be provided');
        }

        return $this->outcomes[$index % count($this->outcomes)];
    }
}
