<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome\Fake;

use Carl\Outcome\Outcome;
use Carl\Outcome\SuccessfulOutcome;
use Carl\Request\Request;
use Carl\Response\Fake\FixedResponse;
use Carl\Response\Fake\WithRequestUrl;
use Override;

/**
 * Always returns a successful outcome.
 *
 * HTTP code and body can be configured,
 * defaults to 200 and "OK".
 *
 * @codeCoverageIgnore
 */
final readonly class AlwaysSuccessful implements FakeOutcomes
{
    public function __construct(
        private int $code = 200,
        private string $body = 'OK',
    ) {
    }

    #[Override]
    public function at(int $index, Request $request): Outcome
    {
        return new SuccessfulOutcome(
            $request,
            new WithRequestUrl(
                new FixedResponse($this->code, $this->body),
                $request
            ),
        );
    }
}
