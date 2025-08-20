<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome\Fake;

use Carl\Outcome\Outcome;
use Carl\Request\Request;

/**
 * Provides fake outcomes for testing.
 *
 * @codeCoverageIgnore
 */
interface FakeOutcomes
{
    /**
     * @param int $index Position of the request in the batch
     * @param Request $request The request being faked
     * @return Outcome Predefined fake outcome
     */
    public function at(int $index, Request $request): Outcome;
}
