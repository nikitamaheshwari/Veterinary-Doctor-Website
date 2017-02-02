<?php

/*
 * Discussion Board Notices class
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CT_DB_Admin_Notices extends CT_DB_Admin {

	public function __construct() {
		//
	}

	/*
	 * Initialize the class and start calling our hooks and filters
	 * @since 1.0.0
	 */

	public function init() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'admin_notices', array( $this, 'review_request' ) );
		add_action( 'wp_ajax_dismiss_notice', array( $this, 'dismiss_notice_callback' ) );
	}
	
	/**
	 * Inform user of version 2.0.0
	 * Initiated in CT_DB_Admin_Upgrades::update_plugin_version
	 * @since 2.0.0
	*/
	public function major_upgrade() {
		?>
			<div class="notice notice-warning is-dismissible">
				<p>
					<strong><?php _e( 'Discussion Board Upgrade', 'wp-discussion-board' ); ?></strong>
				</p>
				<p><?php _e( 'It looks like you\'ve just updated to Version 2 from a previous version of Discussion Board. Please note that the plugin settings and topics are now all under the Discussion Board menu item in the left hand navigation menu - just under Comments and above Appearance. As always, if you have any questions or comments please <a href="mailto:info@catapultthemes.com">contact me</a> directly or post a topic on <a target="_blank" href="https://discussionboard.pro/">the website</a>.', 'wp-discussion-board' ); ?></p>
					<p><?php _e( 'Thank you for using my plugin.', 'wp-discussion-board' ); ?></p>
			</div>
		<?php
	}
	
	/**
	 * Request a review
	 * @since 1.6.0
	*/
	public function review_request() {
		// Check activation date
		$activation = get_option( 'ctdb_activation_time' );
		if( empty( $activation ) ) {
			// Enter activation time if empty
			update_option( 'ctdb_activation_time', time() );
		} else {
			// See if the plugin has been active for a week
			// 7 days - 604800 seconds;
			$now = time();
			if( absint( $now ) > absint ( $activation + 604800 ) ) {
				// Show notification
				// Check if notice has already been dismissed
				$dismissed = get_option( 'ctdb_review_notice_dismissed' );
				if( empty( $dismissed ) ) { ?>
					<div class="notice notice-info is-dismissible" data-notice="review_notice">
						<p>
							<strong><?php _e( 'Discussion Board', 'wp-discussion-board' ); ?></strong>: <?php _e( 'Hi. It looks like you have been using Discussion Board for a while now. If you are finding it useful we would really appreciate it if you could leave us a 5 star rating for the plugin on the WordPress repository. Positive reviews and feedback help us to continue developing the plugin.', 'wp-discussion-board' ); ?>
						</p>
						<p>
							<?php _e( 'Don\'t forget: if you have any questions or problems regarding the plugin, please contact us directly and we will get straight back to you.', 'wp-discussion-board' ); ?>
						</p>
						<p>
							<?php _e( 'Many thanks for your support.', 'wp-discussion-board' ); ?>
						</p>
						<p>
							<a class="button button-primary" href="https://wordpress.org/support/plugin/wp-discussion-board/reviews/"><?php _e( 'Leave a 5* review', 'wp-discussion-board' ); ?></a>
							<a class="button button-secondary" href="https:/catapultthemes.com/contact/"><?php _e( 'Contact us', 'wp-discussion-board' ); ?></a>
							<a class="dismiss-text button button-secondary" data-notice="review_notice" href="#"><?php _e( 'Dismiss message', 'wp-discussion-board' ); ?></a>
						</p>
					</div>
					<script>
						jQuery(document).ready(function($){
							$('body').on('click','.notice-dismiss',function(){
								var notice = $(this).parent().attr('data-notice');
								var data = {
									'action': 'dismiss_notice',
									'notice': notice,
									'security': "<?php echo wp_create_nonce ( 'dismiss_notice' ); ?>",
								}
								$.post(ajaxurl,data);
							});
							$('body').on('click','.dismiss-text',function(){
								var notice = $(this).attr('data-notice');
								var data = {
									'action': 'dismiss_notice',
									'notice': notice,
									'security': "<?php echo wp_create_nonce ( 'dismiss_notice' ); ?>",
								}
								$.post(ajaxurl,data);
								$(this).parent().parent().fadeOut();
							});
						});
					</script>
				<?php }
			};
		}
		
	}
	
	/**
	 * Admin notices
	 * @since 1.1.0
	*/
	public function admin_notices() {
		// Check if notice has already been dismissed
		$dismissed = get_option( 'ctdb_pro_notice_dismissed' );
		
		if( empty( $dismissed ) ) {
			
			$activation = get_option( 'ctdb_activation_time' );
			if( empty( $activation ) ) {
				// Enter activation time if empty
				update_option( 'ctdb_activation_time', time() );
			} else {
				// See if the plugin has been active for 2 days
				// 2 days - 172800 seconds;
				$now = time();
				if( absint( $now ) > absint ( $activation + 172800 ) ) {
	
					// Tell the world there's a pro version
					if ( ! class_exists ( 'CT_DB_Pro_Public' ) ) { ?>
						<div class="notice notice-info is-dismissible" data-notice="pro_notice">
							<p>
								<strong><?php _e( 'Discussion Board', 'wp-discussion-board' ); ?></strong>: <?php _e( 'Did you know that there is a Pro version of the Discussion Board plugin available? It features multiple boards for different subjects, categories and tags, topic following, user profiles, WYSIWYG and image uploads.', 'wp-discussion-board' ); ?>
							</p>
							<p>
								<strong><?php _e( 'Save 20%', 'wp-discussion-board' ); ?></strong>: <?php _e( 'Use the discount code UPGRADE and get 20% off the cost of the Pro version.', 'wp-discussion-board' ); ?>
							</p>
							<p>
								<a class="button button-primary" href="https://discussionboard.pro/?utm_source=pro_notice&utm_medium=wp_plugin&utm_content=ctdb&utm_campaign=dbpro"><?php _e( 'View the demo', 'wp-discussion-board' ); ?></a>
								<a class="dismiss-text button button-secondary" data-notice="pro_notice" href="#"><?php _e( 'Dismiss message', 'wp-discussion-board' ); ?></a>
							</p>
						</div>
						<script>
							jQuery(document).ready(function($){
								$('body').on('click','.notice-dismiss',function(){
									var notice = $(this).parent().attr('data-notice');
									var data = {
										'action': 'dismiss_notice',
										'notice': 'pro_notice',
										'security': "<?php echo wp_create_nonce ( 'dismiss_notice' ); ?>",
									}
									$.post(ajaxurl,data);
								});
								$('body').on('click','.dismiss-text',function(){
									var notice = $(this).attr('data-notice');
									var data = {
										'action': 'dismiss_notice',
										'notice': 'pro_notice',
										'security': "<?php echo wp_create_nonce ( 'dismiss_notice' ); ?>",
									}
									$.post(ajaxurl,data);
									$(this).parent().parent().fadeOut();
								});
							});
						</script>
					<?php }
				}
			}
		}
	}
	
	/**
	 * Dismiss notices
	 * @since 1.1.0
	*/
	public function dismiss_notice_callback() {
		check_ajax_referer( 'dismiss_notice', 'security' );
		$notice = sanitize_text_field( $_POST['notice'] );
		$option = 'ctdb_' . $notice . '_dismissed';
		update_option( $option, 1 );
	}

}

global $CT_DB_Admin_Notices;
$CT_DB_Admin_Notices = new CT_DB_Admin_Notices();
$CT_DB_Admin_Notices -> init();