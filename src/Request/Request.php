<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

/**
 * Describes a request that can be converted into a set of cURL options.
 *
 * Used by HTTP clients to encapsulate request configuration
 * in an object-oriented and composable way.
 */
interface Request
{
    /**
     * Returns the cURL-compatible options for this request.
     *
     * These options are merged and passed to a cURL handle.
     * Common options include CURLOPT_URL, CURLOPT_POSTFIELDS, etc.
     *
     * @return array<int|string, mixed> cURL-compatible options.
     */
    public function options(): array;
}
