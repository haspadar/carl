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
use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;

use function is_string;

use Override;

/**
 * Fake outcome that extracts an HTTP status code from the URI path.
 *
 * Useful in tests when you want to simulate responses with different
 * status codes without real HTTP requests.
 *
 * Example:
 * new FakeClient(new FakeStatus())
 *     ->outcome(new GetRequest("http://localhost/404"))
 *     ->response()
 *     ->info()
 *     ->value(CURLINFO_RESPONSE_CODE); // 404
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
        $code = filter_var($segment, FILTER_VALIDATE_INT);
        if ($code === false) {
            $code = 200;
        }

        return new SuccessfulOutcome(
            $request,
            new CurlResponse(
                'ok',
                [],
                new CurlInfo([CURLINFO_RESPONSE_CODE => $code]),
            ),
        );
    }
}
