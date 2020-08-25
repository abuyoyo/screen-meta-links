# Screen Meta Links

API for adding custom `screen-meta-links` links and panels alongside the 'Screen Options' and 'Help' links on the WordPress admin page.

This is a modified fork of Janis Elsts' library of the same name and is compatible with other plugins using this library.
Library can be imported both as a WordPress plugin or composer library.

## [0.10]https://github.com/abuyoyo/screen-meta-links/releases/tag/0.10)

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
