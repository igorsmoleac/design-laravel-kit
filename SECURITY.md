# Security Policy

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 0.1.x   | :white_check_mark: |

## Reporting a Vulnerability

If you discover a security vulnerability in Design Laravel Kit, please
**do not open a public issue**. Instead, report it privately.

**Contact:** Igor Smoleac — rekeenstudio@gmail.com

Please include:

- A description of the vulnerability
- Steps to reproduce
- Affected versions
- Potential impact
- Any suggested mitigation (optional)

**What to expect:**

- **Acknowledgement** within 48 hours
- **Initial assessment** within 5 business days
- **Fix and coordinated disclosure** — we aim to release a patch within
  30 days of a confirmed vulnerability
- **Credit** in the CHANGELOG and GitHub Release, unless you prefer to
  remain anonymous

## Scope

This package is a **UI component library**. It does not perform
authentication, persist user data, or execute user input as code.

However, it **does render user-provided data** in Blade templates:

- Form components read `session()->getOldInput()` and `$errors` from
  the session and reflect them in HTML attributes (`value`, `checked`,
  `selected`).
- Components that accept `href`, `image`, or similar props render
  user-provided URLs.

We take the following categories seriously:

- **XSS** in component rendering — unescaped user input in Blade
  templates, `{!! !!}` usage, or attribute injection
- **CSRF** — bypasses or omissions in layout meta tags
- **Path traversal** in asset publishing commands
- **Open redirects** in components that render `href` attributes
- **Dependency vulnerabilities** in Bootstrap Italia or other direct deps

## Out of Scope

- Vulnerabilities in the host Laravel application (not our code)
- Issues in Bootstrap Italia itself — please report them to
  [italia/bootstrap-italia](https://github.com/italia/bootstrap-italia/issues)
- Vulnerabilities in dev-only dependencies (PHPUnit, Pint, etc.)

## Disclosure Policy

We follow **coordinated disclosure**:

1. Reporter submits privately
2. We confirm and develop a fix
3. We release a patch version
4. We publish a GitHub Security Advisory
5. Reporter is credited (optional)

We kindly ask reporters to **not** publish details before a patch is
released, giving users time to update.
