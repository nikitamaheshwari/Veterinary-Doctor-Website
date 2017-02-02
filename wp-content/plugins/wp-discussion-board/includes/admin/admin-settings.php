<?php
/*
 * Functions and data for the admin
 * Includes our settings
 *
 * @since 2.1.0
 * @todo transition all settings to arrays
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Returns an array of settings for the General tab
 * This is partial at the moment
 *
 * @since 2.1.0
 * @returns Array
 * @todo add remaining settings to this array
 */
if( ! function_exists( 'ctdb_general_page_settings' ) ) {
	function ctdb_general_page_settings() {
		$settings = array(
			'options_page_settings' => array(
				'id'		=> 'options_page_settings',
				'label'		=> '<h3>' . __( 'Pages', 'wp-discussion-board' ) . '</h3>',
				'callback'	=> 'page_header_callback',
				'page'		=> 'ctdb_options',
				'section'	=> 'ctdb_options_settings',
			),
			'new_topic_page' => array(
				'id'			=> 'new_topic_page',
				'label'			=> __( 'New topic form page', 'wp-discussion-board' ),
				'callback'		=> 'pages_select_callback',
				'description'	=> __( 'The page where your New Topic form is displayed. The [discussion_board_form] shortcode should be on this page.', 'wp-discussion-board', 'wp-discussion-board' ),
				'page'			=> 'ctdb_options',
				'section'		=> 'ctdb_options_settings',
			),
			'discussion_topics_page' => array(
				'id'			=> 'discussion_topics_page',
				'label'			=> __( 'Discussion topics page', 'wp-discussion-board' ),
				'callback'		=> 'pages_select_callback',
				'description'	=> __( 'The page where your Discussion Topics are displayed. The [discussion_topics] shortcode should be on this page.', 'wp-discussion-board' ),
				'page'			=> 'ctdb_options',
				'section'		=> 'ctdb_options_settings',
			),
			'frontend_login_page' => array(
				'id'			=> 'frontend_login_page',
				'label'			=> __( 'Log-in form page', 'wp-discussion-board' ),
				'callback'		=> 'pages_select_callback',
				'description'	=> __( 'The page that displays your log-in form. The [discussion_board_login_form] shortcode should be on this page.', 'wp-discussion-board' ),
				'page'			=> 'ctdb_options',
				'section'		=> 'ctdb_options_settings',
			),
			'redirect_to_page' => array(
				'id'			=> 'redirect_to_page',
				'label'			=> __( 'Redirect on log-in', 'wp-discussion-board' ),
				'callback'		=> 'pages_select_callback',
				'description'	=> __( 'After logging in, the user will be redirected to this page.', 'wp-discussion-board' ),
				'page'			=> 'ctdb_options',
				'section'		=> 'ctdb_options_settings',
			)
		);
		$settings = apply_filters( 'ctdb_general_page_settings', $settings );
		
		return $settings;
	}
}