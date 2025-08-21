<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

final readonly class CurlPayload
{
    public function __construct(private string $raw)
    {
    }

    public function lastHeaderBlock(): string
    {
        preg_match_all(
            '/^HTTP\/[\d.]+\s+\d+\s+[^\r\n]*(?:\r?\n[^\r\n]*)*?\r?\n\r?\n/m',
            $this->raw,
            $matches,
        );
        /** @var array{0: list<string>} $matches */
        $blocks = $matches[0];
        $last = end($blocks);

        if ($last === false) {
            return '';
        }

        return rtrim($last, "\r\n");
    }

    /** @return array<string,string> */
    public function headers(): array
    {
        $lines = preg_split('/\r?\n/', $this->lastHeaderBlock()) ?: [];
        array_shift($lines);

        $headers = [];
        foreach ($lines as $line) {
            if (preg_match('/^([^:]+):\s*(.*)$/', $line, $matches)) {
                $name = trim($matches[1]);
                $value = trim($matches[2]);
                $headers[$name] = isset($headers[$name]) ? $headers[$name] . ', ' . $value : $value;
            }
        }
        return $headers;
    }

    /**
     * Returns the body part of the raw payload,
     * safely skipping all HTTP response headers.
     * Supports multi-response (e.g. with redirects),
     * even if body contains "\r\n\r\n" sequences.
     */
    public function body(): string
    {
        $result = preg_match_all(
            '/^HTTP\/[\d.]+\s+\d+\s+[^\r\n]*(?:\r?\n[^\r\n]*)*?\r?\n\r?\n/m',
            $this->raw,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        if ($result === false || $result === 0) {
            return $this->raw;
        }

        /** @var list<array{string, int<-1, max>}> $blocks */
        $blocks = $matches[0];
        /** @var array{string, int} $last */
        $last = end($blocks);

        [$block, $offset] = $last;
        $start = $offset + strlen($block);

        return substr($this->raw, $start);
    }

    public function raw(): string
    {
        return $this->raw;
    }
}
