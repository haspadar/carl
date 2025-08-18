<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome\Fake;

use Carl\Outcome\FailedOutcome;
use Carl\Outcome\Outcome;
use Carl\Request\Request;
use Override;

/**
 * Always returns a failed outcome.
 *
 * Useful for simulating errors in tests.
 */
final readonly class AlwaysFails implements FakeOutcomes
{
    #[Override]
    public function at(int $index, Request $request): Outcome
    {
        return new FailedOutcome($request, 'Always fails for testing');
    }
}
