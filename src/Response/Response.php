<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

/**
 * Represents an HTTP response.
 *
 * Provides access to response body, headers and cURL transfer info.
 * Used by Outcomes to wrap results of HTTP requests.
 */
interface Response
{
    /**
     * Raw response body as string.
     */
    public function body(): string;

    /**
     * cURL transfer info associated with this response.
     * Useful for retrieving HTTP status codes, timing info, etc.
     */
    public function info(): CurlInfo;

    /**
     * Response headers as an associative array.
     *
     * @return array<string,string> HTTP headers.
     *         Keys are case-insensitive; implementation may or may not preserve original casing
     */
    public function headers(): array;
}
