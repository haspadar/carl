<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Carl\Exception;
use Override;

/**
 * Adds or replaces the query string in the request URL.
 *
 * Parses the original `CURLOPT_URL`, strips any existing query string,
 * and appends the given parameters, preserving fragments (if present).
 *
 * Throws {@see Exception} if original URL is missing or not a string.
 *
 * Example:
 * new WithQuery($request, ['page' => 2, 'tags' => ['php', 'curl']]);
 *
 * Resulting URL:
 * https://example.com/resource?page=2&tags=php&tags=curl
 *
 * @param array<string, scalar|list<scalar>> $params Query parameters (RFC3986-encoded)
 */
final readonly class WithQuery implements Request
{
    /**
     * @param array<string, scalar|list<scalar>> $params
     */
    public function __construct(
        private Request $origin,
        private array $params,
    ) {
    }

    #[Override]
    public function options(): array
    {
        $options = $this->origin->options();
        if (!array_key_exists(CURLOPT_URL, $options) || !is_string($options[CURLOPT_URL])) {
            throw new Exception('Origin request must provide string CURLOPT_URL');
        }

        $originalUrl = $options[CURLOPT_URL];

        $fragmentParts = explode('#', $originalUrl, 2);
        $urlWithoutFragment = $fragmentParts[0];
        $fragmentPart = count($fragmentParts) === 2 ? '#' . $fragmentParts[1] : '';

        $queryParts = explode('?', $urlWithoutFragment, 2);
        $baseUrl = $queryParts[0];

        $queryString = http_build_query($this->params, '', '&', PHP_QUERY_RFC3986);

        $options[CURLOPT_URL] = $baseUrl
            . ($queryString !== '' ? '?' . $queryString : '')
            . $fragmentPart;

        return $options;
    }
}
