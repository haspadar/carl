<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Sends an HTTP DELETE request to the given URL.
 *
 * This class is a predefined wrapper around cURL options
 * for issuing a DELETE request using the `CURLOPT_CUSTOMREQUEST` option.
 */
final readonly class DeleteRequest implements Request
{
    public function __construct(
        private string $url,
    ) {
    }

    #[Override]
    public function options(): array
    {
        return [
            CURLOPT_URL => $this->url,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_RETURNTRANSFER => true,
        ];
    }
}
