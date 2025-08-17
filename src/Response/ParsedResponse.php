// review: noop

<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

final readonly class ParsedResponse
{
    public function __construct(private string $raw)
    {
    }

    public function lastHeaderBlock(): string
    {
        preg_match_all(
            '/^HTTP\/[\d.]+\s+\d+\s+[^\r\n]*\r?\n(?:[^\r\n]+\r?\n)*?\r?\n/m',
            $this->raw,
            $matches,
        );
        /** @var array{0: list<string>} $matches */
        $blocks = array_map(
            fn (string $match): string => trim($match),
            $matches[0],
        );
        $last = end($blocks);

        return $last === false ? '' : $last;
    }

    /** @return array<string,string> */
    public function headers(): array
    {
        $lines = preg_split('/\r?\n/', $this->lastHeaderBlock()) ?: [];
        array_shift($lines);

        /** @var array<string,string> $headers */
        $headers = [];
        foreach ($lines as $line) {
            if (preg_match('/^([^:]+):\s*(.+)$/', $line, $matches)) {
                $headers[trim($matches[1])] = trim($matches[2]);
            }
        }

        return $headers;
    }

    public function body(): string
    {
        $header = $this->lastHeaderBlock();
        $offset = strpos($this->raw, $header);

        return $offset === false
            ? $this->raw
            : substr($this->raw, $offset + strlen($header));
    }
}
