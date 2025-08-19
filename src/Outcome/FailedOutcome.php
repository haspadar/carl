<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome;

use Carl\Exception;
use Carl\Reaction\Reaction;
use Carl\Request\Request;
use Carl\Response\Response;
use Override;

/**
 * Represents a failed outcome of a request.
 *
 * Stores the original {@see Request} and the error message.
 * When reacted upon, it triggers {@see Reaction::onFailure()}.
 * Calling {@see response()} will always throw an {@see Exception}.
 */
final readonly class FailedOutcome implements Outcome
{
    public function __construct(
        private Request $request,
        private string $error
    ) {
    }

    #[Override]
    public function request(): Request
    {
        return $this->request;
    }

    #[Override]
    public function react(Reaction $reaction): void
    {
        $reaction->onFailure($this->request, $this->error);
    }

    #[Override]
    public function response(): Response
    {
        throw new Exception("Outcome failed: $this->error");
    }

}
