<?php

declare(strict_types=1);

namespace Carl\Request;

use Override;

/**
 * Adds an HTTP header only if it hasn’t been added yet.
 *
 * Checks if the given header name (case-insensitive)
 * is already present in the list of `CURLOPT_HTTPHEADER`.
 * If so, leaves the headers unchanged.
 * Otherwise, appends the header to the list.
 *
 * Prevents accidental duplication of headers like `Accept` or `Content-Type`
 * in case of multiple decorators.
 *
 * Decorates another {@see Request}.
 */
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
