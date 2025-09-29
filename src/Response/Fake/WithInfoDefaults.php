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
    public function __construct(private Response $origin) {}

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
     * Timings: *_time values are seconds (float); total_time_us is microseconds (int).
     *
     * @throws RandomException
     */
    #[Override]
    /**
     * Returns CurlInfo with default values.
     * Timing fields are generated monotonically.
     */
    #[Override]
    public function info(): CurlInfo
    {
        $dns = random_int(100, 1_000) / 1_000_000;
        $connect = $dns + random_int(9_000, 49_000) / 1_000_000;
        $start = $connect + random_int(20_000, 200_000) / 1_000_000;
        $totalUs = max(random_int(50_000, 500_000), (int)ceil(($start + 0.001) * 1_000_000));

        return new CurlInfo(array_merge([
            'http_code' => 200,
            'total_time' => $totalUs / 1_000_000,
            'total_time_us' => $totalUs,
            'namelookup_time' => $dns,
            'connect_time' => $connect,
            'appconnect_time' => 0.0,
            'pretransfer_time' => $connect,
            'starttransfer_time' => $start,
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
        ], $this->origin->info()->all()));
    }
}
