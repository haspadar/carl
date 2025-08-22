<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Support\Server;

use Carl\Exception;

final readonly class WaitForPortServer implements Server
{
    public function __construct(private Server $origin)
    {
    }

    #[\Override]
    public function start(): RunningServer
    {
        $server = $this->origin->start();

        $start = microtime(true);
        do {
            $fp = @fsockopen($server->host(), $server->port(), $errno, $error, 0.1);
            if (is_resource($fp)) {
                fclose($fp);
                break;
            }

            if ((microtime(true) - $start) > 5) {
                throw new Exception("Timeout: Port {$server->host()}:{$server->port()} is not available");
            }

            usleep(50_000);
        } while (true);

        return $server;
    }
}
