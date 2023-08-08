# Screen Meta Links

API for adding custom `screen-meta-links` links and panels alongside the 'Screen Options' and 'Help' links on the WordPress admin page.

## [0.13](https://github.com/abuyoyo/screen-meta-links/releases/tag/0.13)

### Fixed
- Fix PHP 8.2 depreacted: Optional parameter declared before required parameter.

## [0.12](https://github.com/abuyoyo/screen-meta-links/releases/tag/0.12)

### Fixed
- Fix PHP notice: Constant already defined.

## [0.11](https://github.com/abuyoyo/screen-meta-links/releases/tag/0.11)

### Removed
- Drop support for `add_screen_meta_link`. This is a backward-incopatible change!

### Added
- New API function `wph_add_screen_meta_panel` replaces `add_screen_meta_link`.

### Changed
- Style is printed in inline style tag.

### Fixed
- Fix conflict with other plugins importing `add_screen_meta_link` such as `broken-link-checker` (issue #1).

## [0.10](https://github.com/abuyoyo/screen-meta-links/releases/tag/0.10)

### Added
- Add `composer.json` file. Library can be imported as composer library.
- Add `CHANGELOG.md` file
- Github action - create release on push tag.

## [0.9](https://github.com/abuyoyo/screen-meta-links/releases/tag/0.9)
- Initial release.
- Modified fork of Janis Elsts' library function `add_screen_meta_link`.

### Added
- Library can be imported as WordPress plugin.
- Ability to insert panels to screen-meta-links.
- Inline (render-blocking) javascript adds meta-links panel and button\link at run-time. Before `screenMeta.init()` script in WordPress's `common.js`. Allowing for full integration.


### Changed
- Add optional `panel` parameter to function `add_screen_meta_link`. This is backward-compatible with Janis Elsts' function of the same name, and any plugins using the original function.
- Rewrite screen-meta-link registration process. Optimized for performance.
