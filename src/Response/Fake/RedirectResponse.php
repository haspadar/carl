<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response\Fake;

use Carl\Response\CurlInfo;
use Carl\Response\Response;
use Override;

/**
 * @codeCoverageIgnore
 *
 * Fake HTTP response that always represents a redirect (302).
 *
 * Useful in tests for simulating redirect behavior,
 * including `Location` header and cURL redirect info.
 *
 * Example:
 * $response = new RedirectResponse("https://example.com/next");
 * echo $response->headers()['Location'];      // https://example.com/next
 * echo $response->info()->value('http_code'); // 302
 */
final readonly class RedirectResponse implements Response
{
    public function __construct(
        private string $location,
        private string $message = 'Redirecting...'
    ) {
    }

    #[Override]
    public function body(): string
    {
        return $this->message;
    }

    #[Override]
    public function headers(): array
    {
        return [
            'Content-Type' => 'text/plain',
            'Location' => $this->location,
        ];
    }

    #[Override]
    public function info(): CurlInfo
    {
        return new CurlInfo([
            'http_code'    => 302,
            'redirect_url' => $this->location,
        ]);
    }
}