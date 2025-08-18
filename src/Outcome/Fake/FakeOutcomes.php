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
 * Strategy for producing fake outcomes in tests.
 *
 * Implementations decide how to return an Outcome
 * for a given request and index.
 */
interface FakeOutcomes
{
    public function at(int $index, Request $request): Outcome;
}
