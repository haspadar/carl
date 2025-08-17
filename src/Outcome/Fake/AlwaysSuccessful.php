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
use Carl\Response\BasicResponse;
use Carl\Response\CurlInfo;
use Override;

final readonly class AlwaysSuccessful implements FakeOutcomes
{
    public function __construct(
        private int $code = 200,
        private string $body = 'OK'
    ) {
    }

    #[Override]
    public function at(int $index, Request $request): Outcome
    {
        return new SuccessfulOutcome(
            $request,
            new BasicResponse(
                $this->body,
                ['Content-Type' => 'text/plain'],
                new CurlInfo([CURLINFO_RESPONSE_CODE => $this->code])
            )
        );
    }
}
