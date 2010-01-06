=== Linkify Categories ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: categories, link, linkify, archives, list, template tag, coffee2code
Requires at least: 2.8
Tested up to: 2.9.1
Stable tag: 1.1
Version: 1.1

Turn a string, list, or array of category IDs and/or slugs into a list of links to those categories.

== Description ==

Turn a string, list, or array of category IDs and/or slugs into a list of links to those categories.

These are all valid calls:

`<?php linkify_categories(43); ?>`
`<?php linkify_categories("43"); ?>`
`<?php linkify_categories("books"); ?>`
`<?php linkify_categories("43 92 102"); ?>`
`<?php linkify_categories("book movies programming-notes"); ?>`
`<?php linkify_categories("book 92 programming-notes"); ?>`
`<?php linkify_categories("43,92,102"); ?>`
`<?php linkify_categories("book,movies,programming-notes"); ?>`
`<?php linkify_categories("book,92,programming-notes"); ?>`
`<?php linkify_categories("43, 92, 102"); ?>`
`<?php linkify_categories("book, movies, programming-notes"); ?>`
`<?php linkify_categories("book, 92, programming-notes"); ?>`
`<?php linkify_categories(array(43,92,102)); ?>`
`<?php linkify_categories(array("43","92","102")); ?>`
`<?php linkify_categories(array("book","movies","programming-notes")); ?>`
`<?php linkify_categories(array("book",92,"programming-notes")); ?>`

Examples:

`<?php linkify_categories("43 92"); ?>`
Displays something like:
    `<a href="http://yourblog.com/category/books">Books</a>, <a href="http://yourblog.com/category/movies">Movies</a>`

`<?php linkify_categories("43, 92", "<li>", "</li>", "</li><li>"); ?></ul>`
Displays something like:
    `<ul><li><a href="http://yourblog.com/category/books">Books</a></li>
    <li><a href="http://yourblog.com/category/movies">Movies</a></li></ul>`

`<?php linkify_categories(""); // Assume you passed an empty string as the first value ?>`
Displays nothing.

`<?php linkify_categories("", "", "", "", "", "No related categories."); // Assume you passed an empty string as the first value ?>`
Displays:
No related categories.


== Installation ==

1. Unzip `linkify-categories.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Use the `linkify_categories()` template tag in one of your templates (be sure to pass it at least the first argument indicating what category IDs and/or slugs to linkify -- the argument can be an array, a space-separate list, or a comma-separated list).  Other optional arguments are available to customize the output.


== Changelog ==

= 1.1 =
* Add PHPDoc documentation
* Add title attribute to link for each category
* Minor formatting tweaks
* Note compatibility with WP 2.9+
* Drop compatibility with WP older than 2.8
* Update copyright date
* Update readme.txt (including adding Changelog)

= 1.0 =
* Initial release