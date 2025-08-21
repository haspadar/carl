<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds a `Referer` header to the request.
 *
 * Useful for simulating browser-like behavior or bypassing
 * referer-based access restrictions.
 *
 * This decorator ensures that the `Referer` header is added only once.
 * If the header already exists, it will not be duplicated.
 *
 * Example:
 * new WithReferer($request, 'https://example.com');
 */
final readonly class WithReferer implements Request
{
    public function __construct(
        private Request $origin,
        private string $referer
    ) {
    }

    #[Override]
    public function options(): array
    {
        return new WithHeaderOnce($this->origin, 'Referer', $this->referer)->options();
    }
}
