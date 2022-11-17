# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.1.3] - 2022-11-17

### Fixed
- Add ignore attribute to YouTube placeholder images, Cookiebot may otherwise block those as well.

## [1.1.1] - 2022-06-20

### Changed
- composer/installers minimum version requirement.

## [1.1.0] - 2021-12-09

### Changed
- Handle ACF oEmbed formatting separately.

## [1.0.2] - 2021-12-08

### Changed
- Trigger filter after ACF oEmbed to ensure we're dealing with HTML, not the raw video URL.

## [1.0.1] - 2021-04-20

### Changed
- Always include the placeholder to avoid non-placeholder version hitting cache.
- Disable parsing the cookie server-side (for now).
- Add class to placeholder hiding it initially to avoid content flashing.

## [1.0.0] - 2021-04-06

### Added
- The first release version.
