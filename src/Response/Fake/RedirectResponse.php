<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response\Fake;

use Carl\Response\CurlInfo;
use Carl\Response\Response;

use const CURLINFO_REDIRECT_URL;
use const CURLINFO_RESPONSE_CODE;

use Override;

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
            CURLINFO_RESPONSE_CODE => 302,
            CURLINFO_REDIRECT_URL => $this->location,
        ]);
    }
}
