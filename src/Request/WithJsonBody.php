<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use JsonException;
use Override;

/**
 * Adds a JSON-encoded body to the request.
 *
 * Encodes the given array as JSON and sets it as the POST body
 * using the `CURLOPT_POSTFIELDS` option.
 *
 * Decorates another {@see Request}.
 *
 * Typically used together with {@see WithJsonContentType} to set the
 * `Content-Type: application/json` header.
 *
 * Example:
 * new WithJsonBody($request, ['foo' => 'bar']);
 *
 * @throws JsonException If encoding fails.
 * @param array<string|int, mixed> $payload The data to encode as JSON.
 */
final readonly class WithJsonBody implements Request
{
    /**
     * @param array<string|int, mixed> $payload
     */
    public function __construct(
        private Request $origin,
        private array $payload,
    ) {
    }

    #[Override]
    public function options(): array
    {
        $options = $this->origin->options();
        $options[CURLOPT_POSTFIELDS] = json_encode($this->payload, JSON_THROW_ON_ERROR);
        return $options;
    }
}
