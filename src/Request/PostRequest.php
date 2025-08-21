<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * POST request wrapper with URL and string body.
 *
 * Sets CURLOPT_POST, CURLOPT_POSTFIELDS and enables response capture.
 */
final readonly class PostRequest implements Request
{
    public function __construct(private string $url, private string $body = '')
    {
    }

    #[Override]
    public function options(): array
    {
        $options = [
            CURLOPT_URL => $this->url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
        ];
        if ($this->body !== '') {
            $options[CURLOPT_POSTFIELDS] = $this->body;
        }

        return $options;
    }
}
