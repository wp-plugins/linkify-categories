<?php
/**
 * @package Linkify_Categories
 * @author Scott Reilly
 * @version 2.0.2
 */
/*
Plugin Name: Linkify Categories
Version: 2.0.2
Plugin URI: http://coffee2code.com/wp-plugins/linkify-categories/
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Turn a list of category IDs and/or slugs into a list of links to those categories.

Compatible with WordPress 2.8+, 2.9+, 3.0+, 3.1+, 3.2+, 3.3+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/linkify-categories/

*/

/*
Copyright (c) 2009-2012 by Scott Reilly (aka coffee2code)

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

require_once( dirname( __FILE__ ) . '/linkify-categories.widget.php' );

if ( ! function_exists( 'c2c_linkify_categories' ) ) :
/**
 * Displays links to each of any number of categories specified via category IDs and/or slugs
 *
 * @since 2.0
 *
 * @param int|string|array $categories A single category ID/slug, or multiple category IDs/slugs defined via an array, or multiple category IDs/slugs defined via a comma-separated and/or space-separated string
 * @param string $before (optional) Text to appear before the entire category listing (if categories exist or if 'none' setting is specified)
 * @param string $after (optional) Text to appear after the entire category listing (if categories exist or if 'none' setting is specified)
 * @param string $between (optional) Text to appear between all categories
 * @param string $before_last (optional) Text to appear between the second-to-last and last element, if not specified, 'between' value is used
 * @param string $none (optional) Text to appear when no categories have been found.  If blank, then the entire function doesn't display anything
 * @return none (Text is echoed; nothing is returned)
 */
function c2c_linkify_categories( $categories, $before = '', $after = '', $between = ', ', $before_last = '', $none = '' ) {
	if ( empty( $categories ) )
		$categories = array();
	elseif ( ! is_array( $categories ) )
		$categories = explode( ',', str_replace( array( ', ', ' ', ',' ), ',', $categories ) );

	if ( empty( $categories ) ) {
		$response = '';
	} else {
		$links = array();
		foreach ( $categories as $id ) {
			if ( 0 == (int) $id ) {
				$cat = get_category_by_slug( $id );
				if ( $cat )
					$id = $cat->cat_ID;
			}
			if ( ! $id )
				continue;
			$title = get_cat_name( $id );
			if ( $title )
				$links[] = sprintf(
					'<a href="%1$s" title="%2$s">%3$s</a>',
					get_category_link( $id ),
					esc_attr( sprintf( __( "View all posts in %s" ), $title ) ),
					$title
				);
		}
		if ( empty( $before_last ) ) {
			$response = implode( $between, $links );
		} else {
			switch ( $size = sizeof( $links ) ) {
				case 1:
					$response = $links[0];
					break;
				case 2:
					$response = $links[0] . $before_last . $links[1];
					break;
				default:
					$response = implode( $between, array_slice( $links, 0, $size-1 ) ) . $before_last . $links[$size-1];
			}
		}
	}
	if ( empty( $response ) ) {
		if ( empty( $none ) )
			return;
		$response = $none;
	}
	echo $before . $response . $after;
}
add_action( 'c2c_linkify_categories', 'c2c_linkify_categories', 10, 6 );
endif;

if ( ! function_exists( 'linkify_categories' ) ) :
/**
 * Displays links to each of any number of categories specified via category IDs and/or slugs
 *
 * @since 1.0
 * @deprecated 2.0 Use c2c_linkify_categories() instead
 */
function linkify_categories( $categories, $before = '', $after = '', $between = ', ', $before_last = '', $none = '' ) {
	_deprecated_function( 'linkify_categories', '2.0', 'c2c_linkify_categories' );
	return c2c_linkify_categories( $categories, $before, $after, $between, $before_last, $none );
}
add_action( 'linkify_categories', 'linkify_categories', 10, 6 ); // Deprecated

endif;
?>