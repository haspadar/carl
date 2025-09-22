<?php

declare(strict_types=1);

namespace Carl\Response\Fake;

use Carl\Response\CurlInfo;
use Carl\Response\Response;
use Override;

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

    #[Override]
    public function info(): CurlInfo
    {
        $info = $this->origin->info()->all();

        $defaults = [
            'http_code' => 200,
            'total_time_us' => 0,
            'namelookup_time' => 0.0,
            'connect_time' => 0.0,
            'appconnect_time' => 0.0,
            'pretransfer_time' => 0.0,
            'starttransfer_time' => 0.0,
            'redirect_time' => 0.0,
            'redirect_count' => 0,
            'size_download' => 0,
            'size_upload' => 0,
            'speed_download' => 0,
            'speed_upload' => 0,
            'url' => '',
            'primary_ip' => '',
            'content_type' => '',
            'redirect_url' => '',
        ];

        return new CurlInfo(array_merge($defaults, $info));
    }
}
