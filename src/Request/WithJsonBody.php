<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds a JSON-encoded body to the request.
 *
 * Encodes the given array as JSON and sets it as the POST body
 * using the `CURLOPT_POSTFIELDS` option.
 *
 * Decorates another {@see Request}.
 *
 * Example:
 * new WithJsonBody($request, ['foo' => 'bar']);
 *
 * @param array<string|int, mixed> $data The data to encode as JSON.
 *
 * @throws \JsonException If encoding fails.
 */
final readonly class WithJsonBody implements Request
{
    /**
     * @param array<string|int, mixed> $data
     */
    public function __construct(
        private Request $origin,
        private array $data,
    ) {
    }

    #[Override]
    public function options(): array
    {
        $options = $this->origin->options();
        $options[CURLOPT_POSTFIELDS] = json_encode($this->data, JSON_THROW_ON_ERROR);
        return $options;
    }
}
