<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds an `Accept-Encoding` option to the request.
 *
 * Enables decoding of compressed responses from the server,
 * such as `gzip`, `deflate`, or `br`.
 *
 * Examples:
 *     new WithEncoding($request, 'gzip')
 *     new WithEncoding($request, 'gzip, br')   // multiple encodings
 *     new WithEncoding($request, '')           // let libcurl pick any supported encoding
 *
 * Decorates another {@see Request}, appending the cURL `CURLOPT_ENCODING` option.
 */
final readonly class WithEncoding implements Request
{
    public function __construct(
        private Request $origin,
        private string $encoding,
    ) {
    }

    #[Override]
    public function options(): array
    {
        return array_replace(
            $this->origin->options(),
            [CURLOPT_ENCODING => $this->encoding]
        );
    }
}
