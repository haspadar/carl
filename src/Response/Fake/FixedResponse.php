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
use Random\RandomException;

/**
 * @codeCoverageIgnore
 *
 * Fake HTTP response with configurable HTTP code and body.
 * Provides a complete CurlInfo object with default metrics and headers.
 */
final readonly class FixedResponse implements Response
{
    /**
     * @param int $code  HTTP status code to report in CurlInfo
     * @param string $body Response body text
     */
    public function __construct(
        private int $code,
        private string $body,
    ) {
    }

    #[Override]
    public function body(): string
    {
        return $this->body;
    }

    #[Override]
    public function headers(): array
    {
        return [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Length' => (string) strlen($this->body),
            'Server' => 'FakeServer/1.0',
            'Date' => gmdate(DATE_RFC7231),
            'Connection' => 'close',
        ];
    }

    /**
     * @throws RandomException
     */
    #[Override]
    public function info(): CurlInfo
    {
        $dns = (float)(random_int(100, 1_000) / 1_000_000);
        $connect = $dns + (float)(random_int(9_000, 49_000) / 1_000_000);
        $start = $connect + (float)(random_int(20_000, 200_000) / 1_000_000);
        $totalUs = max(
            random_int(50_000, 500_000),
            (int) ceil(($start + 0.001) * 1_000_000.0),
        );

        return new CurlInfo([
            'http_code' => $this->code,
            'total_time' => $totalUs / 1_000_000,
            'total_time_us' => $totalUs,
            'namelookup_time' => $dns,
            'connect_time' => $connect,
            'appconnect_time' => 0.0,
            'pretransfer_time' => $connect,
            'starttransfer_time' => $start,
            'redirect_time' => 0.0,
            'redirect_count' => 0,
            'size_download' => strlen($this->body),
            'size_upload' => 0,
            'speed_download' => 0,
            'speed_upload' => 0,
            'url' => 'http://localhost/fake',
            'primary_ip' => '127.0.0.1',
            'content_type' => 'text/plain; charset=utf-8',
            'redirect_url' => '',
        ]);
    }
}
