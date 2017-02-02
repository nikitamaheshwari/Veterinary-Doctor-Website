<?php
/*
 * Functions for user roles and permissions
 * @since 2.1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'ctdb_author_name', 'ctdb_filter_author_name', 100 );
function ctdb_filter_author_name( $author ) {
	$options = get_option( 'ctdb_user_settings' );
	if( isset( $options['display_user_name'] ) ) {
		$display_as = esc_attr( $options['display_user_name'] );
		return get_the_author_meta( $display_as );
	}
	return $author;
}

/*
 * Returns whether current user is permitted to view content
 * @since 2.1.0
 */
if ( ! function_exists ( 'ctdb_is_user_permitted' ) ) {
	function ctdb_is_user_permitted() {
		
		// Array of user roles
		$roles = ctdb_get_role();
		// Array of permitted roles
		$permitted_roles = ctdb_get_permitted_viewer_roles();

		// Can the user view the content
		$user_can = false;
		if( count( $permitted_roles ) == 1 ) {
			// Permitted roles only contains 'administrator' so everyone can view
			$user_can = true;				
		} else if( ! empty( $roles ) ) {
			foreach( $roles as $role ) {
				if( in_array( $role, $permitted_roles ) ) {
					$user_can = true;
					break;
				}
			}
		}
		
		return $user_can;

	}
}


/*
 * Returns the current user's role
 * @since 2.1.0
 */
if ( ! function_exists ( 'ctdb_get_role' ) ) {
	function ctdb_get_role() {
	
		if( ! is_user_logged_in() ) {
			// User not logged in
			$role = array();
		} else {
			$user = wp_get_current_user();
			if( empty( $user ) ) {
				$role = array();
			} else {
				$role = ( array ) $user -> roles;
			}
		}
	
		return $role;
	
	}
}

/*
 * Returns permitted user roles for viewing topics
 * @since 1.0.0
 */
if ( ! function_exists ( 'ctdb_get_permitted_viewer_roles' ) ) {
	function ctdb_get_permitted_viewer_roles() {
	
		$options = get_option( 'ctdb_user_settings' );
		if( isset( $options['discussion_board_minimum_role'] ) ) {
			$viewer_roles = $options['discussion_board_minimum_role'];
		} else {
			$viewer_roles = '';
		}
	
		// Admins can always view
		$viewer_roles[] = 'administrator';
	
		return $viewer_roles;
	
	}
}

/*
 * Returns whether current user is permitted to post content
 * @since 1.0.0
 */
if ( ! function_exists ( 'ctdb_is_posting_permitted' ) ) {
	function ctdb_is_posting_permitted() {
		// Array of user roles
		$roles = ctdb_get_role();
	
		// Array of permitted roles
		$permitted_roles = ctdb_get_permitted_poster_roles();
		// Can the user post the content
		$user_do_post = false;
		if( empty( $permitted_roles ) ) {
			// Permitted roles is empty so everyone can post
			$user_do_post = true;		
		} else if( ! empty( $roles ) ) {
			foreach( $roles as $role ) {
				if( in_array( $role, $permitted_roles ) ) {
					$user_do_post = true;
					break;
				}
			}
		}
		return $user_do_post;
	}
}

/*
 * Returns permitted user roles for posting topics
 * @since 1.0.0
 */
if ( ! function_exists ( 'ctdb_get_permitted_poster_roles' ) ) {
	function ctdb_get_permitted_poster_roles() {
		$options = get_option( 'ctdb_user_settings' );
		if( isset( $options['minimum_user_roles'] ) ) {
			$poster_roles = $options['minimum_user_roles'];
		} else {
			$poster_roles = array();
		}
		return $poster_roles;	
	}
}