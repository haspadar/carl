<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome\Fake;

use Carl\Exception;
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
 *  - FailedOutcome with an error message to simulate network errors
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
        if ($this->statuses === []) {
            throw new Exception('RandomOutcomes: $statuses must be a non-empty list of HTTP status codes');
        }

        foreach ($this->statuses as $s) {
            if ($s < 100 || $s > 599) {
                throw new Exception('RandomOutcomes: $statuses must contain only integers in [100,599]');
            }
        }

        if ($this->failureChance < 0 || $this->failureChance > 100) {
            throw new Exception('RandomOutcomes: $failureChance must be between 0 and 100');
        }

        if (random_int(1, 100) <= $this->failureChance) {
            return new FailedOutcome(
                $request,
                "Simulated network failure",
            );
        }

        $statuses = array_values($this->statuses);
        $code = $statuses[random_int(0, count($statuses) - 1)];

        return new SuccessfulOutcome(
            $request,
            new CurlResponse(
                json_encode(['status' => $code, 'message' => "response:$code"], JSON_THROW_ON_ERROR),
                ['Content-Type' => 'application/json; charset=utf-8'],
                new CurlInfo([CURLINFO_RESPONSE_CODE => $code]),
            ),
        );
    }
}
