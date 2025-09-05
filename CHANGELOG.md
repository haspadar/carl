# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

## [0.8.0] – 2025-09-05

### Added
- Fake responses now include required headers and info fields ([#31](https://github.com/haspadar/carl/pull/31)).
- New response decorators:
    - `RedirectUrl`: exposes last redirect URL from `CurlInfo`.
    - `RedirectCount`: exposes number of redirects from `CurlInfo`.
    - `TotalTime`: exposes total request duration from `CurlInfo`.

## [0.7.0] – 2025-09-03

### Changed
- ThrottledClient now applies delay once per batch (after each outcomes() call).
- Empty batches do not trigger delay.

## [0.6.0] – 2025-09-01

### Added
- `ReactionList`: composite Reaction that forwards `onSuccess`/`onFailure` to each contained reaction in order.

## [0.5.0] - 2025-09-01

### Changed
- Response layer now uses string keys from `curl_getinfo()` instead of numeric constants. This aligns with PHP curl info array keys and simplifies access in responses and tests.

### Added
- `LimitedClient`: a rate-limiting client decorator with corresponding `LimitedClientTest`.

## [0.4.0] - 2025-08-30

### Added
- `RandomOutcomes`: fake outcomes generator that produces either `SuccessfulOutcome` with a random HTTP status and JSON body, or `FailedOutcome` based on a configurable failure chance. Useful for testing consumers against varied responses.

## [0.3.0] - 2025-08-24

### Added
- `WithFormBody`: decorator for `application/x-www-form-urlencoded` request payloads

### Removed
- Removed legacy support for passing body directly to `PostRequest`

### Breaking
- ⚠️ `PostRequest` no longer accepts a body argument. Use the `WithFormBody` decorator to attach payloads.

## [0.2.0] - 2025-08-23

### Changed
- `Client::outcomes()` now accepts `iterable` instead of `array`
- `ChunkedClient` implementation updated to support `iterable` requests

### Added
- `WaitsForPortServer`: decorator for `PhpServer` that waits until the port is reachable

### Fixed
- Intermittent test failure (`Connection reset by peer`) by ensuring server is ready before requests

---

[0.8.0]: https://github.com/haspadar/carl/compare/v0.7.0...v0.8.0
[0.7.0]: https://github.com/haspadar/carl/compare/v0.6.0...v0.7.0
[0.6.0]: https://github.com/haspadar/carl/compare/v0.5.0...v0.6.0
[0.5.0]: https://github.com/haspadar/carl/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/haspadar/carl/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/haspadar/carl/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/haspadar/carl/releases/tag/v0.2.0