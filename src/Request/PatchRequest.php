<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * PATCH request wrapper with default cURL options.
 *
 * Sets `CURLOPT_CUSTOMREQUEST` to `PATCH` and enables response capture via `CURLOPT_RETURNTRANSFER`.
 * To send a payload, combine with {@see WithCurlOption} to set `CURLOPT_POSTFIELDS`
 * (and a content-type decorator such as {@see WithJsonContentType} when sending JSON).
 */
final readonly class PatchRequest implements Request
{
    public function __construct(private string $url)
    {
    }

    #[Override]
    public function options(): array
    {
        return [
            CURLOPT_URL => $this->url,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_RETURNTRANSFER => true,
        ];
    }
}
