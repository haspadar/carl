<?php

declare(strict_types=1);

namespace Carl\Response\Fake;

use Carl\Response\CurlInfo;
use Carl\Response\Response;
use Override;
use Random\RandomException;

/**
 * Decorator that ensures a Response always has
 * a complete CurlInfo with default values.
 */
final readonly class WithInfoDefaults implements Response
{
    public function __construct(private Response $origin)
    {
    }

    #[Override]
    public function body(): string
    {
        return $this->origin->body();
    }

    #[Override]
    public function headers(): array
    {
        return $this->origin->headers();
    }

    /**
     * @throws RandomException
     */
    #[Override]
    public function info(): CurlInfo
    {
        $info = $this->origin->info()->all();

        $defaults = [
            'http_code' => 200,
            'total_time_us' => random_int(50_000, 500_000),
            'namelookup_time' => random_int(1, 10) / 10_000,
            'connect_time' => random_int(10, 50) / 1_000,
            'appconnect_time' => 0.0,
            'pretransfer_time' => 0.0,
            'starttransfer_time' => random_int(20, 200) / 1_000,
            'redirect_time' => 0.0,
            'redirect_count' => 0,
            'size_download' => 0,
            'size_upload' => 0,
            'speed_download' => 0,
            'speed_upload' => 0,
            'url' => 'http://localhost/fake',
            'primary_ip' => '127.0.0.1',
            'content_type' => 'text/plain; charset=utf-8',
            'redirect_url' => '',
        ];

        return new CurlInfo(array_merge($defaults, $info));
    }
}
