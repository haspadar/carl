<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

/**
 * Value object for extracting the effective URL from a Response.
 *
 * Wraps a Response and exposes the final URL that cURL reports
 * after any redirects. Uses the 'url' key from curl_getinfo().
 *
 * Example:
 * $url = (new EffectiveUrl($response))->value();
 */
final readonly class EffectiveUrl
{
    public function __construct(private Response $response)
    {
    }

    public function value(): string
    {
        return $this->response->info()->value('url');
    }
}
