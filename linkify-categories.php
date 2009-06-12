<?php
/*
Plugin Name: Linkify Categories
Version: 1.0
Plugin URI: http://coffee2code.com/wp-plugins/linkify-categories
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Turn a list of category IDs and/or slugs into a list of links to those categories.

Compatible with WordPress 2.5+, 2.6+, 2.7+, 2.8+.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://coffee2code.com/wp-plugins/linkify-categories.zip and unzip it into your 
/wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Use the linkify_categories() template tag in one of your templates (be sure to pass it at least the first argument
	indicating what category IDs and/or slugs to linkify -- the argument can be an array, a space-separate list, or a
	comma-separated list).  Other optional arguments are available to customize the output.


Examples:

These are all valid calls:
	<?php linkify_categories(43); ?>
	<?php linkify_categories("43"); ?>
	<?php linkify_categories("books"); ?>
	<?php linkify_categories("43 92 102"); ?>
	<?php linkify_categories("book movies programming-notes"); ?>
	<?php linkify_categories("book 92 programming-notes"); ?>
	<?php linkify_categories("43,92,102"); ?>
	<?php linkify_categories("book,movies,programming-notes"); ?>
	<?php linkify_categories("book,92,programming-notes"); ?>
	<?php linkify_categories("43, 92, 102"); ?>
	<?php linkify_categories("book, movies, programming-notes"); ?>
	<?php linkify_categories("book, 92, programming-notes"); ?>
	<?php linkify_categories(array(43,92,102)); ?>
	<?php linkify_categories(array("43","92","102")); ?>
	<?php linkify_categories(array("book","movies","programming-notes")); ?>
	<?php linkify_categories(array("book",92,"programming-notes")); ?>

<?php linkify_categories("43 92"); ?>
Displays something like:
	<a href="http://yourblog.com/category/books">Books</a>, 
	<a href="http://yourblog.com/category/movies">Movies</a>

<ul><?php linkify_categories("43, 92", "<li>", "</li>", "</li><li>"); ?></ul>
Displays something like:
	<ul><li><a href="http://yourblog.com/category/books">Books</a></li>
	<li><a href="http://yourblog.com/category/movies">Movies</a></li></ul>

<?php linkify_categories(""); // Assume you passed an empty string as the first value ?>
Displays nothing.

<?php linkify_categories("", "", "", "", "", "No related categories."); // Assume you passed an empty string as the first value ?>
Displays:
	No related categories.

*/

/*
Copyright (c) 2009 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/*
	Displays links to each of any number of categories specified via category IDs and/or slugs
	Arguments:
	 $categories: A single category ID/slug, or multiple category IDs/slugs defined via an array, or multiple category IDs/slugs
					defined via a comma-separated and/or space-separated string
	 $before 	: (optional) To appear before the entire category listing (if categories exist or if 'none' setting is specified)
	 $after 	: (optional) To appear after the entire category listing (if categories exist or if 'none' setting is specified)
	 $between	: (optional) To appear between all categories
	 $before_last : (optional) To appear between the second-to-last and last element, if not specified, 'between' value is used	
	 $none		: (optional) To appear when no categories have been found.  If blank, then the entire function doesn't display anything
 */
function linkify_categories($categories, $before = '', $after = '', $between = ', ', $before_last = '', $none = '') {
	if ( empty($categories) )
		$categories = array();
	elseif ( !is_array($categories) )
		$categories = explode(',', str_replace(array(', ', ' ', ','), ',', $categories));

	if ( empty($categories) ) $response = '';
	else {
		$links = array();
		foreach ( $categories as $id ) {
			if ( 0 == (int)$id ) {
				$cat = get_category_by_slug($id);
				$id = $cat->cat_ID;
			}
			$title = get_cat_name($id);
			if ( $title )
				$links[] = '<a href="' . get_category_link($id) . '">' . $title . '</a>';
		}
		if ( empty($before_last) ) $response = implode($links, $between);
		else {
			switch ( $size = sizeof($links) ) {
				case 1:
					$response = $links[0];
					break;
				case 2:
					$response = $links[0] . $before_last . $links[1];
					break;
				default:
					$response = implode(array_slice($links,0,$size-1), $between) . $before_last . $links[$size-1];
			}
		}
	}
	if ( empty($response) ) {
		if ( empty($none) ) return;
		$response = $none;
	}
	echo $before . $response . $after;
}
?>