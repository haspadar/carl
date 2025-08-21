<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

final readonly class GetRequest implements Request
{
    /**
     * Sends an HTTP GET request to the given URL.
     *
     * Wraps cURL options for executing a GET request. Can be decorated with additional
     * options like headers, timeouts, or query parameters.
     */
    public function __construct(private string $url)
    {
    }

    #[Override]
    public function options(): array
    {
        return [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ];
    }
}
