# Screen Meta Links

> Easily add screen-meta-link panels to WordPress admin pages

## Description

API for adding custom screen-meta-links alongside the "Screen Options" and "Help" links on WordPress admin pages.

This plugin is a fork/rewrite of [Janis Elsts's Screen-Meta-Links Class](https://w-shadow.com/blog/2010/06/30/add-new-buttons-alongside-screen-options-and-help/) and is fully compatible with all plugins that use Janis Elsts's `add_screen_meta_link()`.

The page registration process has been rewritten/optimized.
This plugin will add itself to global `$ws_screen_options_versions` as version 2.0

## Added

- Page registration optimized
- Can add panels (not just button links like the original)

## Usage

```php

/**
 * Add a new link to the screen meta area.
 *
 * This function can be called on current_screen hook (priority < 100) or earlier (admin_init is fine)
 * Plugin begins heavy-lifting (filtering and processing) on current_screen priority 100
 *
 * @param string        $id - Link ID. Should be unique and a valid value for a HTML ID attribute.
 * @param string        $text - Link text.
 * @param string        $href - Optional. Link URL to be used if no panel is provided
 * @param string|array  $page - The page(s) where you want to add the link.
 * @param array         $attributes - Optional. Additional attributes for the link tag.
 *                                              Add 'aria-controls' => "{$id}-wrap" to toggle panel
 * @param callback      $panel - Optional. Callback should print out screen-meta panel contents (html)
 *
 * @return void
 */
add_screen_meta_link( $id, $text, $href, $page, $attributes, $panel );

```
