<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Tests\Integration\Support;

use Carl\Tests\Integration\Support\Server\PhpServer;
use Carl\Tests\Integration\Support\Server\RunningServer;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use Random\RandomException;

trait WithRunningServer
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private RunningServer $server;

    /**
     * @throws RandomException
     */
    #[Before]
    protected function startServer(): void
    {
        $this->server = new PhpServer('127.0.0.1', random_int(8000, 9000))->start();
        $this->waitForServer($this->server);
    }

    #[After]
    protected function stopServer(): void
    {
        $this->server->stop();
    }

    protected function server(): RunningServer
    {
        return $this->server;
    }

    /**
     * Polls the server until it responds, up to timeout.
     * Avoids race conditions on slower environments.
     */
    private function waitForServer(RunningServer $server, int $timeoutMs = 2000): void
    {
        $nowMs = (int) (microtime(true) * 1000.0);
        $deadline = $nowMs + $timeoutMs;

        do {
            $ch = curl_init($server->url('/status/204'));
            if ($ch === false) {
                usleep(20_000);
                continue;
            }

            curl_setopt_array($ch, [
                CURLOPT_NOBODY => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT_MS => 100,
            ]);

            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($code === 204) {
                return;
            }

            usleep(20_000);
        } while ((int) (microtime(true) * 1000.0) < $deadline);
    }
}
