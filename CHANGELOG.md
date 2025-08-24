# Changelog

All notable changes to this project will be documented in this file.

## [0.2.0] – 2025-08-23

### Changed
- `Client::outcomes()` now accepts `iterable` instead of `array`
- `ChunkedClient` implementation updated to support `iterable` requests

### Added
- `WaitsForPortServer`: decorator for `PhpServer` that waits until the port is reachable

### Fixed
- Intermittent test failure (`Connection reset by peer`) by ensuring server is ready before requests

## [0.3.0] – 2025-08-24

### Added
- `WithFormBody`: decorator for `application/x-www-form-urlencoded` request payloads

### Removed
- Removed legacy support for passing body directly to `PostRequest`

### Breaking
- ⚠️ `PostRequest` no longer accepts a body argument. Use the `WithFormBody` decorator to attach payloads.