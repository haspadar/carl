<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome;

use Carl\Request\Request;
use Carl\Response\CurlInfo;
use Carl\Response\CurlPayload;
use Carl\Response\CurlResponse;
use CurlHandle;

/**
 * Builds an {@see Outcome} from a finished {@see CurlHandle}.
 *
 * If cURL reports an error code, produces a {@see FailedOutcome}.
 * Otherwise, wraps the response content into a {@see SuccessfulOutcome}
 * with {@see CurlResponse} and {@see CurlInfo}.
 */
final readonly class OutcomeFromHandle
{
    public function __construct(
        private CurlHandle $handle,
        private Request $request,
    ) {
    }

    public function value(): Outcome
    {
        $errno = curl_errno($this->handle);
        $error = curl_error($this->handle);
        if ($errno !== 0) {
            return new FailedOutcome($this->request, $error);
        }

        $rawContent = curl_multi_getcontent($this->handle);
        $raw = is_string($rawContent) ? $rawContent : '';
        $payload = new CurlPayload($raw);

        return new SuccessfulOutcome(
            $this->request,
            new CurlResponse(
                $payload->body(),
                $payload->headers(),
                new CurlInfo(curl_getinfo($this->handle)),
            )
        );
    }
}
