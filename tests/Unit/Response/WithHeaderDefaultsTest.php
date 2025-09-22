<?php

declare(strict_types=1);

namespace Carl\Tests\Unit\Response\Fake;

use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\Fake\WithHeaderDefaults;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithHeaderDefaultsTest extends TestCase
{
    #[Test]
    public function addsDefaultHeaders(): void
    {
        $response = new WithHeaderDefaults(
            new CurlResponse('abc', [], new CurlInfo([])),
        );

        $headers = $response->headers();

        $this->assertArrayHasKey('Content-Type', $headers, 'Should contain Content-Type');
        $this->assertArrayHasKey('Content-Length', $headers, 'Should contain Content-Length');
        $this->assertArrayHasKey('Server', $headers, 'Should contain Server');
        $this->assertArrayHasKey('Date', $headers, 'Should contain Date');
        $this->assertArrayHasKey('Connection', $headers, 'Should contain Connection');
    }

    #[Test]
    public function preservesOriginalHeaders(): void
    {
        $response = new WithHeaderDefaults(
            new CurlResponse('x', ['X-Custom' => '42'], new CurlInfo([])),
        );

        $headers = $response->headers();

        $this->assertSame('42', $headers['X-Custom'], 'Should keep original header');
    }
}
