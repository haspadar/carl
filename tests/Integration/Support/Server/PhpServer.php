<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Support\Server;

use RuntimeException;

/**
 * Immutable object for starting a PHP built-in server.
 *
 * This class does not represent a running server itself,
 * but a recipe for starting one. By calling {@see PhpServer::start()},
 * you receive a {@see RunningServer} that manages the process
 * and can be explicitly stopped after tests.
 *
 * Usage:
 * $server = new PhpServer('127.0.0.1', 8080)->start();
 * // ... run HTTP requests ...
 * $server->stop();
 */
final readonly class PhpServer
{
    public function __construct(
        private string $host,
        private int $port,
    ) {
    }

    public function start(): RunningServer
    {
        $proc = proc_open(
            [
                'php', '-S', "{$this->host}:{$this->port}", '-t', __DIR__
            ],
            [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );

        if ($proc === false) {
            $this->closePipes($pipes);
            throw new RuntimeException('Failed to start PHP built-in server');
        }

        $this->closePipes($pipes);

        return new RunningServer($proc, $this->host, $this->port);
    }

    private function closePipes(array $pipes): void
    {
        foreach ($pipes as $p) {
            fclose($p);
        }
    }
}
