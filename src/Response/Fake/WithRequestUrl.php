<?php

declare(strict_types=1);

namespace Carl\Response\Fake;

use Carl\Request\Request;
use Carl\Response\CurlInfo;
use Carl\Response\Response;
use Override;

/**
 * @codeCoverageIgnore
 *
 * Decorator that injects Request URL into CurlInfo.
 */
final readonly class WithRequestUrl implements Response
{
    public function __construct(
        private Response $origin,
        private Request $request
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    #[Override]
    public function body(): string
    {
        return $this->origin->body();
    }

    /**
     * @codeCoverageIgnore
     */
    #[Override]
    public function headers(): array
    {
        return $this->origin->headers();
    }

    #[Override]
    public function info(): CurlInfo
    {
        $options = $this->request->options();
        $url = $options[CURLOPT_URL] ?? '';
        $url = $url === '' ? 'http://localhost/fake' : $url;

        return new CurlInfo(array_merge(
            $this->origin->info()->all(),
            ['url' => $url]
        ));
    }
}
