<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response\Fake;

use Carl\Response\CurlInfo;
use Carl\Response\Response;
use Override;

/**
 * @codeCoverageIgnore
 *
 * Fake HTTP response representing a client error (HTTP 400).
 *
 * Useful in tests to simulate scenarios where
 * the request is invalid or rejected by the server.
 */
final readonly class ClientErrorResponse implements Response
{
    public function __construct(private string $message = 'Bad Request')
    {
    }

    #[Override]
    public function body(): string
    {
        return $this->message;
    }

    #[Override]
    public function headers(): array
    {
        return [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Length' => (string)strlen($this->message),
            'Server' => 'FakeServer/1.0',
            'Date' => gmdate('D, d M Y H:i:s') . ' GMT',
            'Connection' => 'close',
        ];
    }

    #[Override]
    public function info(): CurlInfo
    {
        return new CurlInfo([
            'http_code' => 400,
            'total_time' => 0.001,
            'namelookup_time' => 0.0,
            'connect_time' => 0.0,
            'appconnect_time' => 0.0,
            'pretransfer_time' => 0.0,
            'starttransfer_time' => 0.001,
            'redirect_time' => 0.0,
            'redirect_count' => 0,
            'size_download' => strlen($this->message),
            'size_upload' => 0,
            'speed_download' => strlen($this->message) * 1000,
            'speed_upload' => 0,
            'url' => 'http://fake.local/client-error',
            'primary_ip' => '127.0.0.1',
            'content_type' => 'text/plain; charset=utf-8',
            'redirect_url' => '',
        ]);
    }
}
