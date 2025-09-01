<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Reaction;

use Carl\Request\Request;
use Carl\Response\Response;
use Override;

/**
 * Reaction that delegates to multiple reactions.
 *
 * Useful when you need to apply several side effects (metrics,
 * logging, tracing, etc.) to the same outcomes.
 *
 * Example:
 * $reaction = new CompositeReaction([
 *     new OnSuccess(fn() => print "ok1\n"),
 *     new OnSuccess(fn() => print "ok2\n"),
 */
final readonly class CompositeReaction implements Reaction
{
    /** @var list<Reaction> */
    private array $reactions;

    /**
     * @param list<Reaction> $reactions
     */
    public function __construct(array $reactions)
    {
        $this->reactions = $reactions;
    }

    #[Override]
    public function onSuccess(Request $request, Response $response): void
    {
        foreach ($this->reactions as $reaction) {
            $reaction->onSuccess($request, $response);
        }
    }

    #[Override]
    public function onFailure(Request $request, string $error): void
    {
        foreach ($this->reactions as $reaction) {
            $reaction->onFailure($request, $error);
        }
    }
}
