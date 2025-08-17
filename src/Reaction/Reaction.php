<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Reaction;

use Carl\Request\Request;
use Carl\Response\Response;

interface Reaction
{
    public function onSuccess(Request $request, Response $response): void;
    public function onFailure(Request $request, string $error): void;
}
