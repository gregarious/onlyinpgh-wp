=== HTML Helpers ===
Contributors: karevn
Donate link: mailto:karev.n@gmail.com
Tags: html, api, code, php, plugin, simple, template, generator
Requires at least: 1.5
Tested up to: 3.1.3
Stable tag: 0.2.3

This plugin adds simple HTML tag generation API to WordPress.

== Description ==

This plugin adds simple HTML tag generation API to WordPress, like these examples:
`<?php img('wordpress'); ?>` generates and prints `<img src="/wp-content/<your-theme>/images/wordpress.png" alt="Wordpress" />`.
`<?php _img('http://example.com/images/wordpress.png', 'alt', array('id' => 'my-image')) ?>` generates and returns almost the same tag, just with absolute url, id and alt attributes set manually.
`<?php select('category', collection2options(get_terms('category'), 'term_id', 'name'), $term->term_id) ?>` generates select tag with options taken from WP 'category' taxonomy and sets $term as selected item
`<?php cycle(array('odd', 'even')); reset_cycle(); ?>` rails-like "cycle" that returns "odd" on first call, "even" on second, "odd" on
    third call, so on. reset_cycle resets the cycle.
`<?php the_post_meta('meta_name') ?>` shortcut for echo(get_post_meta($id, 'meta_name', true))

Form field generators for `text`, `checkbox`, `hidden` input types, `textarea`, and more tags. More docs coming soon, you can read the code to figure out other features for now.



== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `html-helpers` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use html-helpers API in your plugins and themes.

OR

1. Put html-helpers.php to your plugin or theme folder
2. `require` it
3. Use html-helpers API in your plugins and themes.

== Changelog ==

= 0.2.3 =
* Removed unecessary closing tags for img, input
* _img and img support image names without an extension
* smarter 'alt' attribute handling for img

= 0.2.2 =
* Fixed endless recursion for img function

= 0.2.1 =
* Added `cycle, `reset_cycle` and `_cycle` functions to support rails-style cycles
* Added "multiple" option support for `select` tag.

= 0.2 =
* Initial public release