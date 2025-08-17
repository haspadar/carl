// review: noop

<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

final readonly class PutRequest implements Request
{
    public function __construct(private string $url)
    {
    }

    #[Override]
    public function options(): array
    {
        return [
            CURLOPT_URL => $this->url,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_RETURNTRANSFER => true,
        ];
    }
}
