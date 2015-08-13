<?php

class Linkify_Categories_Test extends WP_UnitTestCase {

	private static $cat_ids = array();

	function setUp() {
		parent::setUp();
		$this->cat_ids = $this->factory->category->create_many( 5 );
	}


	/*
	 *
	 * HELPER FUNCTIONS
	 *
	 */


	function get_slug( $cat_id ) {
		return get_category( $cat_id )->slug;
	}

	function expected_output( $count, $lowest_id, $between = ', ', $cat_num = 1 ) {
		$str = '';
		$j = $lowest_id;
		for ( $n = 1, $i = $cat_num; $n <= $count; $n++, $i++ ) {
			if ( ! empty( $str ) ) {
				$str .= $between;
			}
			$str .= '<a href="http://example.org/?cat=' . $j . '" title="View all posts in Term ' . $i . '">Term ' . $i . '</a>';
			$j++;
		}
		return $str;
	}

	function get_results( $args, $direct_call = true, $use_deprecated = false ) {
		ob_start();

		$function = $use_deprecated ? 'linkify_categories' : 'c2c_linkify_categories';

		if ( $direct_call ) {
			call_user_func_array( $function, $args );
		} else {
			do_action_ref_array( $function, $args );
		}

		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}


	/*
	 *
	 * TESTS
	 *
	 */


	function test_single_id() {
		$this->assertEquals( $this->expected_output( 1, $this->cat_ids[0] ), $this->get_results( array( $this->cat_ids[0] ) ) );
		$this->assertEquals( $this->expected_output( 1, $this->cat_ids[0] ), $this->get_results( array( $this->cat_ids[0], false ) ) );
	}

	function test_array_of_ids() {
		$this->assertEquals( $this->expected_output( 5, $this->cat_ids[0] ), $this->get_results( array( $this->cat_ids ) ) );
		$this->assertEquals( $this->expected_output( 5, $this->cat_ids[0] ), $this->get_results( array( $this->cat_ids ), false ) );
	}

	function test_single_slug() {
		$cat = get_category( $this->cat_ids[0] );
		$this->assertEquals( $this->expected_output( 1, $cat->term_id ), $this->get_results( array( $cat->slug ) ) );
	}

	function test_array_of_slugs() {
		$cat_slugs = array_map( array( $this, 'get_slug' ), $this->cat_ids );
		$this->assertEquals( $this->expected_output( 5, $this->cat_ids[0] ), $this->get_results( array( $cat_slugs ) ) );
		$this->assertEquals( $this->expected_output( 5, $this->cat_ids[0] ), $this->get_results( array( $cat_slugs ), false ) );
	}

	function test_all_empty_categories() {
		$this->assertEmpty( $this->get_results( array( '' ) ) );
		$this->assertEmpty( $this->get_results( array( array() ) ) );
		$this->assertEmpty( $this->get_results( array( array( array(), '' ) ) ) );
	}

	function test_an_empty_category() {
		$cat_ids = array_merge( array( '' ), $this->cat_ids );
		$this->assertEquals( $this->expected_output( 5, $this->cat_ids[0] ), $this->get_results( array( $cat_ids ) ) );
		$this->assertEquals( $this->expected_output( 5, $this->cat_ids[0] ), $this->get_results( array( $cat_ids ), false ) );
	}

	function test_all_invalid_categories() {
		$this->assertEmpty( $this->get_results( array( 99999999 ) ) );
		$this->assertEmpty( $this->get_results( array( 'not-a-category' ) ) );
		$this->assertEmpty( $this->get_results( array( 'not-a-category' ), false ) );
	}

	function test_an_invalid_category() {
		$cat_ids = array_merge( array( 99999999 ), $this->cat_ids );
		$this->assertEquals( $this->expected_output( 5, $this->cat_ids[0] ), $this->get_results( array( $cat_ids ) ) );
		$this->assertEquals( $this->expected_output( 5, $this->cat_ids[0] ), $this->get_results( array( $cat_ids ), false ) );
	}

	function test_arguments_before_and_after() {
		$expected = '<div>' . $this->expected_output( 5, $this->cat_ids[0] ) . '</div>';
		$this->assertEquals( $expected, $this->get_results( array( $this->cat_ids, '<div>', '</div>' ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->cat_ids, '<div>', '</div>' ), false ) );
	}

	function test_argument_between() {
		$expected = '<ul><li>' . $this->expected_output( 5, $this->cat_ids[0], '</li><li>' ) . '</li></ul>';
		$this->assertEquals( $expected, $this->get_results( array( $this->cat_ids, '<ul><li>', '</li></ul>', '</li><li>' ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->cat_ids, '<ul><li>', '</li></ul>', '</li><li>' ), false ) );
	}

	function test_argument_before_last() {
		$before_last = ', and ';
		$expected = $this->expected_output( 4, $this->cat_ids[0] ) . $before_last . $this->expected_output( 1, $this->cat_ids[4], ', ', 5 );
		$this->assertEquals( $expected, $this->get_results( array( $this->cat_ids, '', '', ', ', $before_last ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->cat_ids, '', '', ', ', $before_last ), false ) );
	}

	function test_argument_none() {
		$missing = 'No categories to list.';
		$expected = '<ul><li>' . $missing . '</li></ul>';
		$this->assertEquals( $expected, $this->get_results( array( array(), '<ul><li>', '</li></ul>', '</li><li>', '', $missing ) ) );
		$this->assertEquals( $expected, $this->get_results( array( array(), '<ul><li>', '</li></ul>', '</li><li>', '', $missing ), false ) );
	}

	/**
	 * @expectedDeprecated linkify_categories
	 */
	function test_deprecated_function() {
		$this->assertEquals( $this->expected_output( 1, $this->cat_ids[0] ), $this->get_results( array( $this->cat_ids[0] ), false, true ) );
		$this->assertEquals( $this->expected_output( 5, $this->cat_ids[0] ), $this->get_results( array( $this->cat_ids ), false, true ) );
		$cat = get_category( $this->cat_ids[0] );
		$this->assertEquals( $this->expected_output( 1, $cat->cat_ID ), $this->get_results( array( $cat->slug ), false, true ) );
		$cat_slugs = array_map( array( $this, 'get_slug' ), $this->cat_ids );
		$this->assertEquals( $this->expected_output( 5, $this->cat_ids[0] ), $this->get_results( array( $cat_slugs ), false, true ) );
	}

}
