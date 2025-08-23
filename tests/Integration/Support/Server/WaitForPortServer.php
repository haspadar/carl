<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Support\Server;

use Carl\Exception;
use Override;
use Throwable;

final readonly class WaitForPortServer implements Server
{
    public function __construct(private Server $origin)
    {
    }

    #[Override]
    /**
     * Starts the server and waits until its TCP port becomes reachable.
     *
     * If the port doesn't become available within 5 seconds, attempts to stop
     * the server and throws a diagnostic exception with the last socket error.
     */
    public function start(): RunningServer
    {
        $server = $this->origin->start();
        $start = microtime(true);

        do {
            $fp = @fsockopen($server->host(), $server->port(), $lastErrno, $lastError, 0.1);
            if (is_resource($fp)) {
                fclose($fp);
                break;
            }

            if ((microtime(true) - $start) > 5.0) {
                try {
                    $server->stop();
                } catch (Throwable) {
                    // ignore
                }

                throw new Exception(
                    "Timeout: Port {$server->host()}:{$server->port()} is not available "
                    . "(last error: [{$lastErrno}] {$lastError})"
                );
            }

            usleep(50_000);
        } while (true);

        return $server;
    }
}
