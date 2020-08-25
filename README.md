# Screen Meta Links API

> Easily add screen-meta-links panels to WordPress admin pages

## Description

API for adding custom screen-meta-links alongside the "Screen Options" and "Help" links on WordPress admin pages.

This library uses render-blocking javascript to get get around WordPress's lack of API for adding tabs to the screen-meta-links.

Screen-Meta-Links API is a fork/rewrite and expansion of [Janis Elsts's Screen-Meta-Links Class](https://w-shadow.com/blog/2010/06/30/add-new-buttons-alongside-screen-options-and-help/) and is fully compatible with all plugins that use Janis Elsts's `add_screen_meta_link()`. It allows adding fully functional panels to any (or all admin page).

## Installation

### WordPress Plugin
Screen-Meta-Links API can be installed as a WordPress plugin by dropping this directory into the `plugins` directory and activating from the Plugins page.

### Library
Screen-Meta-Links can also be used as library by using Composer

```bash
composer install abuyoyo\screen-meta-links
```

## Added

- Can add panels (not just button links like the original)
- Page registration optimized


## Compatibility with original Screen-Meta-Links classes

- The `add_screen_meta_link()` signature is backwards-compatible with original function.
- The page registration process has been rewritten/optimized but delivers the same results.
- $page parameter accepts single string or array of strings. Either file string `index.php` or name `dashboard`. Use `*` to display panel on all pages. Empty string will disable panel on all pages.
- If only `$href` is provided without corresponding `$panel` - a simple link will be added.
- If both `$href` and `$panel` are provided - a button and panel are added.

## Usage

```php

/**
 * Add a new link to the screen meta area.
 *
 * This function can be called on current_screen hook (priority < 100) or earlier (admin_init is fine)
 * Plugin begins heavy-lifting (filtering and processing) on current_screen priority 100
 *
 * @param string           $id         - Link ID. Should be unique and a valid HTML ID attribute.
 * @param string           $text       - Link text. The text appearing on the tab.
 * @param string           $href       - Optional. Link URL to be used if no panel is provided
 *                                       Support for `add_screen_meta_link` original usage.
 * @param string|string[]  $page       - The page(s) where you want to add the link.
 * @param array            $attributes - Optional. Additional attributes for the link tag.
 *                                       Add 'aria-controls' => "{$id}-wrap" to toggle panel
 * @param callback         $panel      - Optional. Callback should echo screen-meta panel HTML content.
 *
 * @return void
 */
add_screen_meta_link( $id, $text, $href, $page, $attributes, $panel );

```

### The `$page` Parameter

The `$page` parameter accepts a string or array of strings.  
Accepts `page`, `post`, `dashboard` etc.  
Or actual file name: `post.php`, `index.php` etc. (`index.php` and `dashboard` will resolve to the same page).  
Accepts custom page id's: `toplevel_page_my-plugin` etc.  
Accepts wildcard: `*` - This will add the meta-screen-panel to all admin pages.  
