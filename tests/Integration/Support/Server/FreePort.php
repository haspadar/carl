<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Support\Server;

use RuntimeException;

final readonly class FreePort
{
    public function __construct(private string $host = '127.0.0.1')
    {
    }

    /**
     * @throws RuntimeException
     */
    public function value(): int
    {
        $socket = @stream_socket_server("tcp://{$this->host}:0", $errno, $errstr);
        if (!is_resource($socket)) {
            throw new RuntimeException("Failed to acquire ephemeral port: [$errno] $errstr");
        }

        $name = stream_socket_get_name($socket, false);
        fclose($socket);

        if ($name === false) {
            throw new RuntimeException("Failed to get socket name");
        }

        [, $port] = explode(':', $name);
        return (int)$port;
    }
}
