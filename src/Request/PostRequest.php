<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * POST request wrapper with only URL.
 *
 * Sets `CURLOPT_POST` and enables response capture.
 * Does not set a body — use decorators such as {@see WithFormBody}, {@see WithJsonBody}, or {@see WithBody}
 * to add a request payload.
 *
 * Example:
 * new WithJsonBody(
 *     new WithJsonContentType(
 *         new PostRequest($url)
 *     ),
 *     ['foo' => 'bar']
 * );
 *
 * Decorates a `Request` with POST semantics.
 */
final readonly class PostRequest implements Request
{
    public function __construct(private string $url)
    {
    }

    #[Override]
    public function options(): array
    {
        return [
            CURLOPT_URL => $this->url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
        ];
    }
}
