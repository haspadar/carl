<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

final readonly class PrimaryIp
{
    public function __construct(private Response $response)
    {
    }

    public function value(): string
    {
        return $this->response->info()->value(CURLINFO_PRIMARY_IP, '0.0.0.0');
    }
}
