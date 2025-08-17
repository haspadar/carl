// review: noop

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

use function is_string;

use Override;

final readonly class StatusCodeFromUri implements FakeOutcomes
{
    #[Override]
    public function at(int $index, Request $request): Outcome
    {
        $options = $request->options();
        $url = '';
        if (isset($options[CURLOPT_URL]) && is_string($options[CURLOPT_URL])) {
            $url = $options[CURLOPT_URL];
        }

        $code = (int) parse_url($url, PHP_URL_PATH);

        return new SuccessfulOutcome(
            $request,
            new BasicResponse(
                'ok',
                [],
                new CurlInfo([CURLINFO_HTTP_CODE => $code])
            )
        );
    }
}
