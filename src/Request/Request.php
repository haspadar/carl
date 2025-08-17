<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

interface Request
{
    /**
     * @return array<int|string, mixed> cURL-compatible options (e.g. CURLOPT_URL).
     */
    public function options(): array;
}
