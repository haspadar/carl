<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Outcome;

use Carl\Request\Request;
use Carl\Response\BasicResponse;
use Carl\Response\CurlInfo;
use Carl\Response\ParsedResponse;
use CurlHandle;

final readonly class OutcomeFromHandle
{
    public function __construct(
        private CurlHandle $handle,
        private Request $request
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
        $parsed = new ParsedResponse($raw);

        return new SuccessfulOutcome(
            $this->request,
            new BasicResponse(
                $parsed->body(),
                $parsed->headers(),
                new CurlInfo(curl_getinfo($this->handle))
            )
        );
    }
}
