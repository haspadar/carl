<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Response;

interface Response
{
    public function body(): string;
    public function info(): CurlInfo;

    /** @return array<string,string> */
    public function headers(): array;
}
