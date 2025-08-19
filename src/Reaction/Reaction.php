<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Reaction;

use Carl\Request\Request;
use Carl\Response\Response;

/**
 * Defines callbacks for handling client outcomes.
 *
 * Implementations decide what to do when:
 * - {@see onSuccess()} is invoked for a successful {@see Response};
 * - {@see onFailure()} is invoked when an error occurs.
 *
 * This allows clients to react differently depending on outcome
 * (logging, retrying, collecting metrics, etc.).
 */
interface Reaction
{
    /**
     * Handle a successful response.
     */
    public function onSuccess(Request $request, Response $response): void;

    /**
     * Handle a failure with the given error message.
     */
    public function onFailure(Request $request, string $error): void;
}
