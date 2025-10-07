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

    #[Test]
    public function usesDefaultUrlWhenMissingInRequest(): void
    {
        $response = new WithRequestUrl(
            new CurlResponse('irrelevant', [], new CurlInfo([])),
            new GetRequest('')
        );

        $this->assertSame(
            'http://localhost/fake',
            $response->info()->value('url'),
            'Must use default URL when request provides no CURLOPT_URL'
        );
    }

    #[Test]
    public function replacesExistingUrlInInfo(): void
    {
        $response = new WithRequestUrl(
            new CurlResponse('irrelevant', [], new CurlInfo(['url' => 'old'])),
            new GetRequest('https://example.org/test')
        );

        $this->assertSame(
            'https://example.org/test',
            $response->info()->value('url'),
            'Must replace existing url from origin CurlInfo'
        );
    }

    #[Test]
    public function preservesHttpCodeFromOrigin(): void
    {
        $response = new WithRequestUrl(
            new CurlResponse('irrelevant', [], new CurlInfo(['http_code' => 200])),
            new GetRequest('https://example.org/test')
        );

        $this->assertSame(
            '200',
            $response->info()->value('http_code'),
            'Must preserve existing http_code value'
        );
    }
}
