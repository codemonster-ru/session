# Changelog

All significant changes to this project will be documented in this file.

## [1.0.0] – 2025-10-24

### Added

-   **`Session` facade** — simple static API to manage sessions in any PHP project:

    -   `Session::start()` — initializes a session with file or array handler.
    -   `Session::get()`, `Session::put()`, `Session::forget()`, `Session::all()`, `Session::destroy()`.

-   **`Store` class** — encapsulates individual session state and serialization logic (JSON-based).

-   **Session handlers**:

    -   `FileSessionHandler` — stores session data as JSON files.
    -   `ArraySessionHandler` — in-memory session handler, useful for CLI or testing.
    -   Full compatibility with native PHP `SessionHandlerInterface`.

-   **Custom handler support** — pass any handler implementing `SessionHandlerInterface`.
