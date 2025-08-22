<?php

/*
 * SPDX-FileCopyrightText: Copyright (c) 2025 Kanstantsin Mesnik
 * SPDX-License-Identifier: MIT
 */
declare(strict_types=1);

namespace Carl\Client;

use Carl\Exception;
use Carl\Outcome\Outcome;
use Carl\Outcome\OutcomeFromHandle;
use Carl\Reaction\Reaction;
use Carl\Reaction\VoidReaction;
use Carl\Request\Request;
use CurlHandle;
use Override;
use SplObjectStorage;

/**
 * cURL-based client using curl_multi.
 *
 * Builds easy handles for each Request, drives the multi loop,
 * converts finished handles to Outcomes via {@see OutcomeFromHandle},
 * and applies the given Reaction to each Outcome.
 *
 * @psalm-type MultiOpt = array<int, int|bool>
 */
final readonly class CurlClient implements Client
{
    /**
     * @param array<int, int|bool> $multiOptions curl_multi options passed to curl_multi_setopt
     * @psalm-param MultiOpt $multiOptions
     */
    public function __construct(private array $multiOptions = [])
    {
    }

    #[Override]
    public function outcome(Request $request, Reaction $reaction = new VoidReaction()): Outcome
    {
        return $this->outcomes([$request], $reaction)[0];
    }

    /**
     * Execute requests concurrently via curl_multi and preserve order of completion.
     *
     * For each finished easy handle, an Outcome is produced and the Reaction is invoked.
     * Returns all produced outcomes (completion order, not submission order).
     *
     * @throws Exception When curl_init() fails
     * @return list<Outcome>
     */
    #[Override]
    public function outcomes(iterable $requests, Reaction $reaction = new VoidReaction()): array
    {
        $multiHandle = curl_multi_init();
        foreach ($this->multiOptions as $opt => $val) {
            curl_multi_setopt($multiHandle, $opt, $val);
        }

        /** @var SplObjectStorage<CurlHandle, Request> $handleToRequest */
        $handleToRequest = new SplObjectStorage();

        foreach ($requests as $request) {
            $easyHandle = curl_init();
            if ($easyHandle === false) {
                throw new Exception('curl_init() failed');
            }
            curl_setopt_array($easyHandle, $request->options());
            curl_multi_add_handle($multiHandle, $easyHandle);
            $handleToRequest[$easyHandle] = $request;
        }

        $outcomes = [];

        do {
            curl_multi_exec($multiHandle, $running);
            $selected = curl_multi_select($multiHandle);
            if ($selected === -1) {
                usleep(1_000);
            }

            while (($info = curl_multi_info_read($multiHandle)) !== false) {
                /** @var CurlHandle $completedHandle */
                $completedHandle = $info['handle'];
                /** @var Request $request */
                $request = $handleToRequest[$completedHandle];

                $outcome = new OutcomeFromHandle($completedHandle, $request)->value();
                $outcome->react($reaction);
                $outcomes[] = $outcome;

                curl_multi_remove_handle($multiHandle, $completedHandle);
                curl_close($completedHandle);
                $handleToRequest->detach($completedHandle);
            }
        } while ($running > 0);

        curl_multi_close($multiHandle);

        return $outcomes;
    }
}
