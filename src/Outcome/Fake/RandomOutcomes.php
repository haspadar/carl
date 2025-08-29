<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome\Fake;

use Carl\Outcome\FailedOutcome;
use Carl\Outcome\Outcome;
use Carl\Outcome\SuccessfulOutcome;
use Carl\Request\Request;
use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use JsonException;
use Override;
use Random\RandomException;

/**
 * RandomOutcomes produces random outcomes:
 *  - SuccessfulOutcome with random HTTP status code and JSON body
 *  - FailedOutcome with plain text body to simulate network errors
 *
 * Example:
 *   $client = new FakeClient(new RandomOutcomes());
 *   $outcomes = $client->outcomes([$req1, $req2]);
 *
 * @codeCoverageIgnore
 */
final readonly class RandomOutcomes implements FakeOutcomes
{
    private const array DEFAULT_STATUSES = [200, 201, 301, 400, 401, 403, 404, 429, 500, 502, 503];

    /**
     * @param int[] $statuses
     */
    public function __construct(private array $statuses = self::DEFAULT_STATUSES, private int $failureChance = 10)
    {
    }

    /**
     * @throws JsonException
     * @throws RandomException
     */
    #[Override]
    public function at(int $index, Request $request): Outcome
    {
        if (random_int(1, 100) <= $this->failureChance) {
            return new FailedOutcome(
                $request,
                "Simulated network failure"
            );
        }

        $code = $this->statuses[array_rand($this->statuses)];

        return new SuccessfulOutcome(
            $request,
            new CurlResponse(
                json_encode(['status' => $code, 'message' => "response:$code"], JSON_THROW_ON_ERROR),
                ['Content-Type' => 'application/json'],
                new CurlInfo([CURLINFO_RESPONSE_CODE => $code])
            )
        );
    }
}
