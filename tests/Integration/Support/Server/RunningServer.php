<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Support\Server;

use Carl\Exception;

/**
 * Represents a running PHP built-in server process.
 *
 * Instances of this class are returned by {@see PhpServer::start()}.
 * You can stop the process via {@see RunningServer::stop()} and
 * build URLs pointing to this server with {@see RunningServer::url()}.
 *
 * Example:
 * $server = (new PhpServer('127.0.0.1', 8080))->start();
 * echo $server->url('/ping'); // "http://127.0.0.1:8080/ping"
 * $server->stop();
 */
final readonly class RunningServer
{
    public function __construct(
        private mixed $proc,
        private string $host,
        private int $port,
    ) {
    }

    public function stop(): void
    {
        if (!is_resource($this->proc)) {
            throw new Exception('Server process is already closed');
        }
        @proc_terminate($this->proc);
        @proc_close($this->proc);
    }

    public function url(string $path = '/'): string
    {
        $normalizedPath = $path !== '' && $path[0] === '/' ? $path : "/$path";
        return "http://{$this->host}:{$this->port}{$normalizedPath}";
    }

    public function host(): string
    {
        return $this->host;
    }

    public function port(): int
    {
        return $this->port;
    }
}
