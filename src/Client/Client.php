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

interface Client
{
    /**
     * @param Request[] $requests
     * @return Outcome[]
     */
    public function outcomes(array $requests, Reaction $reaction = new VoidReaction()): array;

    public function outcome(Request $request, Reaction $reaction = new VoidReaction()): Outcome;
}
