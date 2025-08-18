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
        $headers = isset($options[CURLOPT_HTTPHEADER]) && is_array($options[CURLOPT_HTTPHEADER])
            ? array_values($options[CURLOPT_HTTPHEADER])
            : [];

        $needle = strtolower($this->name);
        foreach ($headers as $line) {
            $position = strpos($line, ':');
            if ($position !== false && strtolower(trim(substr($line, 0, $position))) === $needle) {
                $options[CURLOPT_HTTPHEADER] = $headers;
                return $options;
            }
        }

        $headers[] = $this->name . ': ' . $this->value;
        $options[CURLOPT_HTTPHEADER] = $headers;
        return $options;
    }
}
