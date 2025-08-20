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
    private RunningServer $server;

    /**
     * @throws RandomException
     */
    #[Before]
    protected function startServer(): void
    {
        $this->server = new PhpServer('127.0.0.1', random_int(8000, 9000))->start();
        usleep(50_000);
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
}