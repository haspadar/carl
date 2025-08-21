<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome;

use Carl\Reaction\Reaction;
use Carl\Request\Request;
use Carl\Response\Response;

/**
 * Represents the result of executing a {@see Request}.
 *
 * An Outcome may be successful (with a {@see Response}) or failed (with an error).
 * It can also notify a {@see Reaction} about its state.
 */
interface Outcome
{
    public function request(): Request;
    public function react(Reaction $reaction): void;
    public function response(): Response;
    public function isSuccessful(): bool;
}
