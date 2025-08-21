<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds or overrides the User-Agent header.
 *
 * Ensures that the request contains the specified `User-Agent` string.
 * If a User-Agent is already set, it will not be duplicated.
 *
 * Example:
 * new WithUserAgent($request, 'MyApp/1.0');
 */
final readonly class WithUserAgent implements Request
{
    public function __construct(private Request $origin, private string $userAgent)
    {
    }

    #[Override]
    public function options(): array
    {
        return new WithHeaderOnce($this->origin, 'User-Agent', $this->userAgent)->options();
    }
}
