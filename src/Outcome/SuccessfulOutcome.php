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
use Override;

final readonly class SuccessfulOutcome implements Outcome
{
    public function __construct(
        private Request $request,
        private Response $response,
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
        $reaction->onSuccess($this->request, $this->response);
    }

    #[Override]
    public function response(): Response
    {
        return $this->response;
    }
}
