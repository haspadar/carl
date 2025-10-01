<?php

declare(strict_types=1);

namespace Carl\Tests\Unit\Response;

use Carl\Request\GetRequest;
use Carl\Response\CurlInfo;
use Carl\Response\CurlResponse;
use Carl\Response\Fake\WithRequestUrl;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithRequestUrlTest extends TestCase
{
    #[Test]
    public function injectsUrlFromRequestIntoInfo(): void
    {
        $request = new GetRequest('https://example.org/test');

        $response = new WithRequestUrl(
            new CurlResponse('irrelevant', [], new CurlInfo([])),
            $request
        );

        $this->assertSame(
            'https://example.org/test',
            $response->info()->value('url'),
            'Should inject URL from Request into CurlInfo'
        );
    }
}
