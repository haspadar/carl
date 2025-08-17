// review: noop

<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

final readonly class PostRequest implements Request
{
    public function __construct(private string $url, private string $body)
    {
    }

    #[Override]
    public function options(): array
    {
        return [
            CURLOPT_URL => $this->url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->body,
            CURLOPT_RETURNTRANSFER => true,
        ];
    }
}
