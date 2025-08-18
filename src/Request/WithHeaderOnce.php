<?php

declare(strict_types=1);

namespace Carl\Request;

use Override;

final readonly class WithHeaderOnce implements Request
{
    public function __construct(
        private Request $origin,
        private string $name,
        private string $value,
    ) {
    }

    /** @return array<int|string, mixed> */
    #[Override]
    public function options(): array
    {
        $options = $this->origin->options();

        /** @var list<string> $headers */
        $headers = [];
        if (isset($options[CURLOPT_HTTPHEADER]) && is_array($options[CURLOPT_HTTPHEADER])) {
            foreach ($options[CURLOPT_HTTPHEADER] as $h) {
                if (is_scalar($h)) {
                    $headers[] = (string) $h;
                }
            }
        }

        $needle = strtolower(trim($this->name));
        foreach ($headers as $line) {
            [$field] = explode(':', $line, 2) + [''];
            if (strtolower(trim($field)) === $needle) {
                $options[CURLOPT_HTTPHEADER] = $headers;
                return $options;
            }
        }

        $headers[] = sprintf('%s: %s', $this->name, $this->value);
        $options[CURLOPT_HTTPHEADER] = $headers;
        return $options;
    }
}
