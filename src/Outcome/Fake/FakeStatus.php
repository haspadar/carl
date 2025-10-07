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

use function is_string;

use Override;

/**
 * Fake outcome that extracts an HTTP status code from the URI path.
 *
 * Example:
 * new FakeClient(new FakeStatus())
 *     ->outcome(new GetRequest("http://localhost/404"))
 *     ->response()
 *     ->info()
 *     ->value('http_code'); // 404
 *
 * @codeCoverageIgnore
 */
final readonly class FakeStatus implements FakeOutcomes
{
    #[Override]
    public function at(int $index, Request $request): Outcome
    {
        $options = $request->options();
        $url = '';
        if (isset($options[CURLOPT_URL]) && is_string($options[CURLOPT_URL])) {
            $url = $options[CURLOPT_URL];
        }

        $path = (string)parse_url($url, PHP_URL_PATH);
        $segment = basename($path);
        $code = filter_var(
            $segment,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 100, 'max_range' => 599]],
        );
        if ($code === false) {
            $code = 200;
        }

        return new SuccessfulOutcome(
            $request,
            new WithRequestUrl(
                new FixedResponse($code, 'ok'),
                $request
            )
        );
    }
}
