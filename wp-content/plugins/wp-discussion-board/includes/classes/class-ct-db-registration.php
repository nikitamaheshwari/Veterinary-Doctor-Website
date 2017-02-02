<?php
/*
 * Discussion Board registration class
 * This handles user registration and logging in
 *
 * @since 2.1.0
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registration public class
 **/
if( ! class_exists( 'CT_DB_Registration' ) ) { // Don't initialise if there's already a Discussion Board activated
	class CT_DB_Registration {
		
		public $user_can_view = false;
		public $user_can_post = false;
		
		public function __construct() {
			
		}
		/*
		 * Initialize the class and start calling our hooks and filters
		 * @since 1.0.0
		 */
		public function init() {
			
			add_action( 'init', array( $this, 'check_user_permission' ) );
			add_action( 'init', array( $this, 'login_user' ) );
			add_action( 'init', array( $this, 'register_new_user' ) );
			add_action( 'init', array( $this, 'activate_new_user' ) );
			add_action( 'init', array( $this, 'redirect_to_login_page' ) );

			add_action( 'wp_login_failed', array( $this, 'login_fail' ) );
			add_action( 'wp_ajax_ajax_validation', array( $this, 'ajax_validation_callback' ) );
			add_action( 'wp_ajax_nopriv_ajax_validation', array( $this, 'ajax_validation_callback' ) );
			
			add_shortcode( 'discussion_board_login_form', array( $this, 'return_login_registration_form' ), 10, 2 );
			add_shortcode( 'discussion_board_login_only', array( $this, 'return_login_form_only' ), 10, 2 );
		
		}
		
		public function check_user_permission() {
			$this->user_can_view = ctdb_is_user_permitted();
			$this->user_can_post = ctdb_is_posting_permitted();
		}
		
		/*
		 * Return log-in and registration form markup
		 * @since 1.0.0
		 */
		public function return_login_registration_form( $atts, $content = '' ) {
		
			if( ! is_user_logged_in() ) {
			
				$message = '';
				$class = '';
				
				// Check if styles are enqueued
				$options = get_option( 'ctdb_design_settings' );
				
				// Use icons?
				$show_icons = ctdb_use_icons();
				
				// Log in tab
				if( isset( $_POST['ctdb_page'] ) && $_POST['ctdb_page'] != 'register' ) $class='active-header';
				$message .= '<div class="ctdb-header ' . $class . '" data-form-id="ctdb-login-wrap"><h3 class="ctdb-h3">';
				if( $show_icons ) {
					$message .= '<span class="dashicons dashicons-unlock"></span>';
				}
				$message .= __('Log in', 'wp-discussion-board' ) . '</h3></div>';
				
				// If styles are not enqueued we will display the log-in form fields directly after the heading
				if( empty( $options['enqueue_styles'] ) ) {
					$message .= $this -> display_login_form();
				}
				
				// Registration tab
				$class = '';
				if( isset( $_POST['ctdb_page'] ) && $_POST['ctdb_page'] == 'register' ) $class='active-header';
				$message .= '<div class="ctdb-header ' . $class . '" data-form-id="ctdb-registration-wrap"><h3 class="ctdb-h3">';
				if( $show_icons ) {
					$message .= '<span class="dashicons dashicons-edit"></span>';
				}
				$message .= __('Register', 'wp-discussion-board' ) . '</h3></div>';

				// Get the forms HTML
				
				// If styles are enqueued we will display the log-in form fields after both headings
				if( ! empty( $options['enqueue_styles'] ) ) {
					$message .= $this -> display_login_form();
				}
				
				$message .= $this -> display_registration_form();
				
				$message .= '<script>
					jQuery(document).ready(function($){
						$(".ctdb-header").on("click",function(){
							id = $(this).data("form-id");
							$(".ctdb-header").removeClass("active-header");
							$(this).addClass("active-header");
							$(".ctdb-form-section").removeClass("active-section");
							$("#"+id).addClass("active-section");
						});
					});
				</script>';

			} else {

				$message = do_shortcode( $content );
				
			}
			
			return $message;
		
		}
		
		/*
		 * Return log-in form markup
		 * @since 1.3.1
		 */
		public function return_login_form_only( $atts, $content = '' ) {
		
			if( ! is_user_logged_in() ) {
			
				$message = '';
				$class = '';
				
				// Check if styles are enqueued
				$options = get_option( 'ctdb_design_settings' );
				
				// Use icons?
				$show_icons = ctdb_use_icons();
				
				// Log in tab
				$message .= '<div class="ctdb-header active-header" data-form-id="ctdb-login-wrap"><h3 class="ctdb-h3">';
				if( $show_icons ) {
					$message .= '<span class="dashicons dashicons-unlock"></span>';
				}
				$message .= __('Log in', 'wp-discussion-board' ) . '</h3></div>';
				
				// Get the forms HTML				
				$message .= $this -> display_login_form();
				
			} else {

				$message = do_shortcode( $content );
				
			}
			
			return $message;
		
		}
		
		
		/*
		 * Hide comment form if user doesn't have access
		 * @since 1.0.0
		 */
		public function return_access_restricted_title() {
			
			$options = get_option( 'ctdb_options_settings' );
			$title = $options['restricted_title'];
			
			if( $title == '' ) {
				$title = __( 'Content not available', 'wp-discussion-board' );
			}

			return $title;
			
		}

		/*
		 * Display registration form
		 * @since 1.0.0
		 * @credit https://pippinsplugins.com/creating-custom-front-end-registration-and-login-forms/
		 */
		public function display_registration_form() {
			
			// Only show the registration form to non-logged-in members
			if( ! is_user_logged_in() ) {
				
				$output = $this -> registration_form_fields();
				
			} else {
			
				$output = '<div id="ctdb-registration-wrap" class="ctdb-form-section">';
				$output .= '<p>' . __( 'You are already registered on this site.', 'wp-discussion-board' ) . '</p>';
				$output .= '</div>';
				
			}
			
			return $output;
			
		}
		
		/*
		 * Display log-in form
		 * @since 1.0.0
		 * @credit https://pippinsplugins.com/creating-custom-front-end-registration-and-login-forms/
		 */
		public function display_login_form() {

			$output = $this -> login_form_fields();	
			return $output;
			
		}
		
		/*
		 * Get the registration form fields
		 * @since 1.0.0
		 * @credit https://pippinsplugins.com/creating-custom-front-end-registration-and-login-forms/
		 */
		public function registration_form_fields() {
			
			$options = get_option( 'ctdb_options_settings' );
			// Check if we need to add a humanity check to the form
			$require_humanity = isset( $options['check_human'] );
			$class = '';
			
			ob_start();
			
			if( isset( $_POST['ctdb_page'] ) && $_POST['ctdb_page'] == 'register' ) $class='active-section'; ?>
				
			<div id="ctdb-registration-wrap" class="ctdb-form-section <?php echo $class; ?>">
				
				<?php 
				// show any error messages after form submission
				$this->ctdb_show_error_messages(); 
				
				// Get the registration form fields 
				$form = $this->registration_form_fields_array(); ?>
		 
				<form id="ctdb_registration_form" class="ctdb-form" action="" method="POST">
					
					<fieldset>
						
						<?php if( ! empty( $form ) ) {
							foreach( $form as $field ) {
								if( $field['field'] == 'input' ) { 
									if( isset( $_POST["{$field['id']}"] ) ) {
										$value = $_POST["{$field['id']}"];
									} else {
										$value = '';
									} ?>
									<p>
										<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_attr( $field['label'] ); ?><span id="<?php echo esc_attr( $field['id'] ); ?>-response" class="validation-response"></span></label>
										<input name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( $field['class'] ); ?> <?php echo esc_attr( $field['id'] ); ?>" type="<?php echo esc_attr( $field['type'] ); ?>" value="<?php echo $value; ?>"/>
									</p>
								<?php } else if( $field['field'] == 'textarea' ) {
									if( isset( $_POST["{$field['id']}"] ) ) {
										$value = $_POST["{$field['id']}"];
									} else {
										$value = '';
									} ?>
									<p>
										<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_attr( $field['label'] ); ?><span id="<?php echo esc_attr( $field['id'] ); ?>-response" class="validation-response"></span></label>
										<textarea name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( $field['class'] ); ?> <?php echo esc_attr( $field['id'] ); ?>"><?php echo $value; ?></textarea>
									</p>
								<?php }
							}
						} ?>
						<?php if( $require_humanity ) { // Add humanity check ?>
							<p>
								<label><?php _e( 'Are you a human?', 'wp-discussion-board' ); ?></label>
								<input name="ctdb_check_humanity" value="no" type="radio"/> No<br>
								<input name="ctdb_check_humanity" value="yes" type="radio"/> Yes
							</p>
						<?php } ?>
						<p>
							<input type="hidden" name="ctdb_page" value="register"/>
							<input type="hidden" name="ctdb_register_nonce" value="<?php echo wp_create_nonce('ctdb-register-nonce'); ?>"/>
							<input type="submit" value="<?php _e( 'Register Your Account', 'wp-discussion-board' ); ?>"/>
						</p>
						
					</fieldset>
					
					<script>
						// Do inline validation for username
						jQuery(document).ready(function($){
							// On focusout so that we only evaluate inputs once completed
							// Send current so that we only evaluate current input
							$('#ctdb-registration-wrap input').on('focusout',function(){
								var required = 'false';
								if($(this).hasClass('required')){
									required = 'true';
								}
								// Clear the field validation each time
								var validateID = $(this).attr('id');
								$(this).removeClass('valid invalid');
								$('#'+validateID+'-response').html('');
								var data = {
									'action': 'ajax_validation',
									'current': $(this).attr('id'),
									'val': $(this).val(),
									'required': required,
									'login': $('.ctdb_user_login').val(),
									'email': $('#ctdb-registration-wrap #ctdb_user_email').val(),
									'pass': $('#ctdb-registration-wrap #ctdb_user_pass').val(),
									'confirm': $('#ctdb-registration-wrap #ctdb_user_pass_confirm').val(),
									'security': "<?php echo wp_create_nonce ( "validation_nonce" ); ?>",
									'dataType': 'json'
								};
								$.post(ajaxurl, data, function(response){
									response = JSON.parse(response);
									for(var i=0; i<response.length; i++){
										if(response[i]['status']=='error'){
											var id = response[i]['id'];
											$('.'+id).removeClass('valid');
											$('.'+id).addClass('invalid');
											$('#'+id+'-response').html('<small> - '+response[i]['message']+'</small>');
										} else if(response[i]['status']=='ok'){
											var id = response[i]['id'];
											$('.'+id).removeClass('invalid');
											$('.'+id).addClass('valid');
											if(response[i]['message']){
												$('#'+id+'-response').html('<small> - '+response[i]['message']+'</small>');
											}else{
												$('#'+id+'-response').html('');
											}
										}
									}
								});
							});
						});
					</script>
					
				</form>
			</div><!-- .ctdb-form-section .-->
				
			<?php
			
			return ob_get_clean();
			
		}
		
		public function ajax_validation_callback() {
			check_ajax_referer ( 'validation_nonce', 'security' );
			$login_name = sanitize_text_field( $_POST['login'] );
			$val = $_POST['val'];
			$email = $_POST['email'];
			$pass = sanitize_text_field( $_POST['pass'] );
			$confirm = sanitize_text_field( $_POST['confirm'] );
			$is_required = ( $_POST['required'] );
			$current = $_POST['current'];
			$response = array();
			if( $current == 'ctdb_user_login' ) {
				if(	username_exists( $login_name ) && ! empty ( $login_name ) ) {
					$response[] = array(
						'id'		=> 'ctdb_user_login',
						'status'	=> 'error',
						'message'	=> __( 'That username already exists', 'wp-discussion-board' )
					);
				} else if( ! validate_username( $login_name ) && ! empty ( $login_name ) ) {
					$response[] = array(
						'id'		=> 'ctdb_user_login',
						'status'	=> 'error',
						'message'	=> __( 'Only use lower case alphanumeric characters', 'wp-discussion-board' )
					);
				} else if( empty ( $login_name ) && $is_required == true ) {
					$response[] = array(
						'id'		=> $current,
						'status'	=> 'error',
						'message'	=> __( 'This field is required', 'wp-discussion-board' )
					);
				} else if( ( ! empty( $login_name ) ) ) {
					$response[] = array(
						'id'		=> 'ctdb_user_login',
						'status'	=> 'ok',
						'message'	=> __( 'Looks good', 'wp-discussion-board' )
					);
				}
			} else if( $current == 'ctdb_user_email' ) {
				if(	! is_email( $val ) && ! empty ( $val ) ) {
					$response[] = array(
						'id'		=> 'ctdb_user_email',
						'status'	=> 'error',
						'message'	=> __( 'That email doesn\'t look valid', 'wp-discussion-board' )
					);
				} else if( email_exists( $val ) ) {
					$response[] = array(
						'id'		=> 'ctdb_user_email',
						'status'	=> 'error',
						'message'	=> __( 'That email has already been used', 'wp-discussion-board' )
					);
				} else if( empty ( $val ) && $is_required == 'true' ) {
					$response[] = array(
						'id'		=> $current,
						'status'	=> 'error',
						'message'	=> __( 'This field is required', 'wp-discussion-board' )
					);
				} else if( ! empty ( $val ) ) {
					$response[] = array(
						'id'		=> 'ctdb_user_email',
						'status'	=> 'ok',
						'message'	=> __( 'That email looks good', 'wp-discussion-board' )
					);
				}
			} else if( $current == 'ctdb_user_pass' || $current == 'ctdb_user_pass_confirm' ) {
				if(	$confirm == $pass && ! empty( $confirm ) && ! empty( $pass ) ) {
					$response[] = array(
						'id'		=> 'ctdb_user_pass_confirm',
						'status'	=> 'ok',
						'message'	=> __( 'Passwords match', 'wp-discussion-board' )
					);
					$response[] = array(
						'id'		=> 'ctdb_user_pass',
						'status'	=> 'ok',
						'message'	=> ''
					);
				}
				if( $current == 'ctdb_user_pass_confirm' ) {
					if(	$confirm != $pass ) {
						$response[] = array(
							'id'		=> 'ctdb_user_pass_confirm',
							'status'	=> 'error',
							'message'	=> __( 'The passwords don\'t match', 'wp-discussion-board' )
						);
					}
				}
				if( empty( $val ) && $is_required == 'true' ) {
					$response[] = array(
						'id'		=> $current,
						'status'	=> 'error',
						'message'	=> __( 'This field is required', 'wp-discussion-board' )
					);
				} else {
					$response[] = array(
						'id'		=> $current,
						'status'	=> '',
						'message'	=> ''
					);
				}
			} else if( empty( $val ) && $is_required == 'true' ) {
				$response[] = array(
					'id'		=> $current,
					'status'	=> 'error',
					'message'	=> __( 'This field is required', 'wp-discussion-board' )
				);
			} else if( $current != 'ctdb_user_pass' && ! empty( $val ) ){
				$response[] = array(
					'id'		=> $current,
					'status'	=> 'ok',
					'message'	=> __( 'Looks good', 'wp-discussion-board' )
				);
			}
			
			echo wp_json_encode( $response );
			
			wp_die();
		
		}
		
		/*
		 * Return the registration form fields
		 * @since 1.3.0
		 */
		public function registration_form_fields_array() {
			
			$form = array(
				'ctdb_user_login'	=> array(
					'id'		=> 'ctdb_user_login',
					'label'		=> __( 'Username', 'wp-discussion-board' ),
					'field'		=> 'input',
					'type'		=> 'text',
					'class'		=> 'required'
				),
				'ctdb_user_email'	=> array(
					'id'		=> 'ctdb_user_email',
					'label'		=> __( 'Email', 'wp-discussion-board' ),
					'field'		=> 'input',
					'type'		=> 'email',
					'class'		=> 'required'
				),
				'ctdb_user_first'	=> array(
					'id'		=> 'ctdb_user_first',
					'label'		=> __( 'First Name', 'wp-discussion-board' ),
					'field'		=> 'input',
					'type'		=> 'text',
					'class'		=> 'required'
				),
				'ctdb_user_last'	=> array(
					'id'		=> 'ctdb_user_last',
					'label'		=> __( 'Last Name', 'wp-discussion-board' ),
					'field'		=> 'input',
					'type'		=> 'text',
					'class'		=> 'required'
				),
				'ctdb_user_pass'			=> array(
					'id'		=> 'ctdb_user_pass',
					'label'		=> __( 'Password', 'wp-discussion-board' ),
					'field'		=> 'input',
					'type'		=> 'password',
					'class'		=> 'required'
				),
				'ctdb_user_pass_confirm'	=> array(
					'id'		=> 'ctdb_user_pass_confirm',
					'label'		=> __( 'Password again', 'wp-discussion-board' ),
					'field'		=> 'input',
					'type'		=> 'password',
					'class'		=> 'required'
				)
				
			);
			
			$form = apply_filters( 'ctdb_registration_form_fields', $form ); 
			
			return $form;
			
		}
		
		/*
		 * Get the login form fields
		 * @since 1.0.0
		 * @credit https://pippinsplugins.com/creating-custom-front-end-registration-and-login-forms/
		 */
		public function login_form_fields() {
		 
			$class = '';
			
			ob_start();
			
			if( empty( $_POST['ctdb_page'] ) || $_POST['ctdb_page'] != 'register' ) $class='active-section'; ?>
			
			<div id="ctdb-login-wrap" class="ctdb-form-section <?php echo $class; ?>">

				<?php
				// Show any error messages after form submission
				$this -> ctdb_show_error_messages(); ?>
				
				<?php // Password reset notification message ?>
				<?php // @todo better notification method ?>
				<?php if( isset( $_GET['action'] ) && $_GET['action'] == 'passwordrequest' ) { ?>
					<p class="ctdb-success"><?php _e( 'Please check your inbox for an email containing a link to reset your password.', 'wp-discussion-board' ); ?></p>
				<?php } ?>
		 
				<form id="ctdb_login_form" class="ctdb-form" action="" method="post">
				
					<fieldset>
						<p>
							<label for="ctdb_user_login"><?php _e( 'Username', 'wp-discussion-board' ); ?></label>
							<input name="ctdb_user_login" id="ctdb_user_login" class="required" type="text"/>
						</p>
						<p>
							<label for="ctdb_user_pass"><?php _e( 'Password', 'wp-discussion-board' ); ?></label>
							<input name="ctdb_user_pass" id="ctdb_user_pass" class="required" type="password"/>
						</p>
						<p>
							<input type="hidden" name="ctdb_login_nonce" value="<?php echo wp_create_nonce('ctdb-login-nonce'); ?>"/>
							<input id="ctdb_login_submit" type="submit" value="<?php _e( 'Log in', 'wp-discussion-board' ); ?>"/>
						</p>
					</fieldset>
					
				</form>
				
				<p><a href="<?php echo wp_lostpassword_url( get_permalink() . '?action=passwordrequest' ); ?>"><?php _e( 'Lost password?', 'wp-discussion-board' ); ?></a><p>
				
			</div>
			
			<?php
			return ob_get_clean();
		}
		
		/*
		 * Logs user in after submitting form
		 * @since 1.0.0
		 * @credit https://pippinsplugins.com/creating-custom-front-end-registration-and-login-forms/
		 */
		public function login_user() {
			
			if( isset( $_POST['ctdb_user_login'] ) && isset( $_POST['ctdb_login_nonce'] ) && wp_verify_nonce( $_POST['ctdb_login_nonce'], 'ctdb-login-nonce' ) ) {
			
				// this returns the user ID and other info from the user name
				$user = get_user_by( 'login', $_POST['ctdb_user_login'] );
		 
				if( ! $user ) {
					// if the user name doesn't exist
					$this -> ctdb_errors() -> add( 'login_error', __( 'You\'ve entered an invalid combination of username and password.', 'wp-discussion-board' ) );
					return;
				}
				
				$user_roles = $user -> roles;
				
				if( $user_roles ) {
					$user_role = array_shift( $user_roles );
				}
				
				if( $user_role == 'pending' ) {
					// User hasn't activated their registration yet
					$this -> ctdb_errors() -> add( 'unactivated', __( 'You haven\'t activated your registration yet. Please check your email for an activation link', 'wp-discussion-board' ) );
				}
		 
				if( empty( $_POST['ctdb_user_pass'] ) ) {
					// if no password was entered
					$this -> ctdb_errors() -> add( 'empty_password', __( 'Please enter a password', 'wp-discussion-board' ) );
				}
		 
				// check the user's login with their password
				if( ! wp_check_password( $_POST['ctdb_user_pass'], $user -> user_pass, $user->ID ) ) {
					// if the password is incorrect for the specified user
					$this -> ctdb_errors() -> add( 'login_error', __( 'You\'ve entered an invalid combination of username and password.', 'wp-discussion-board' ) );
				}
		 
				// retrieve all error messages
				$errors = $this -> ctdb_errors() -> get_error_messages();
		 
				// only log the user in if there are no errors
				if( empty( $errors ) ) {
					$options = get_option( 'ctdb_options_settings' );
					// Check what page has been set to redirect to
					if( ! empty( $options['redirect_to_page'] ) ) {
						$redirect_to = $options['redirect_to_page'];
					} else {
						$redirect_to = '';
					}
		 
					wp_set_auth_cookie( $user -> ID, false );
					wp_set_current_user( $user -> ID, $_POST['ctdb_user_login'] );	
					do_action( 'wp_login', $_POST['ctdb_user_login'] );
					
					$this -> check_user_permission();
		 
					// Redirect to the correct page
					if( $redirect_to ) {
						wp_redirect( get_permalink( $redirect_to ) );
						exit;
					}
					
				}
				
			}
		}
		
		/*
		 * Registers user after submitting form
		 * @since 1.0.0
		 * @credit https://pippinsplugins.com/creating-custom-front-end-registration-and-login-forms/
		 */
		public function register_new_user() {
		
			global $post;
			
			
			if( empty( $_POST['ctdb_register_nonce'] ) ) {
				return;
			}
			
			$options = get_option( 'ctdb_options_settings' );
			
			// Check if we need to add a humanity check to the form
			if( isset( $options['check_human'] ) ) {
				$require_humanity = $options['check_human'];
			} else {
				$require_humanity = false;
			}
			
			if( $require_humanity && isset( $_POST['ctdb_check_humanity'] ) && $_POST['ctdb_check_humanity'] == 'yes' ) {
				$is_human = true;
			} else if( $require_humanity &&( ! isset( $_POST['ctdb_check_humanity'] ) || $_POST['ctdb_check_humanity'] == 'no' ) ) {
				$is_human = false;
			} else {
				$is_human = true;
			}
			
			if( isset( $_POST["ctdb_user_login"] ) && $is_human && wp_verify_nonce( $_POST['ctdb_register_nonce'], 'ctdb-register-nonce' ) ) {
				
				$user_login = $_POST["ctdb_user_login"];
				$user_email = $_POST["ctdb_user_email"];
				if( isset( $_POST["ctdb_user_first"] ) ) {
					$user_first = $_POST["ctdb_user_first"];
				}
				if( isset( $_POST["ctdb_user_last"] ) ) {
					$user_last = $_POST["ctdb_user_last"];
				}
				$user_pass = $_POST["ctdb_user_pass"];
				$pass_confirm = $_POST["ctdb_user_pass_confirm"];
				if( isset( $_POST["ctdb_url"] ) ) {
					$website = $_POST["ctdb_url"];
				} else {
					$website = '';
				}
				if( isset( $_POST["ctdb_bio"] ) ) {
					$bio = $_POST["ctdb_bio"];
				} else {
					$bio = '';
				}
		 
				// this is required for username checks
			//	require_once( ABSPATH . WPINC . '/registration.php' );
		 
				if( username_exists( $user_login ) ) {
					// Username already registered
					$this -> ctdb_errors() -> add( 'username_unavailable', __( 'That username has already been taken. Please choose another.', 'wp-discussion-board' ) );
				}
				if( ! validate_username( $user_login ) ) {
					// invalid username
					$this -> ctdb_errors() -> add( 'username_invalid', __( 'The username you selected was not valid. Please only use lowercase alphanumeric characters.', 'wp-discussion-board' ) );
				}
				if( $user_login == '' ) {
					// empty username
					$this -> ctdb_errors() -> add( 'username_empty', __( 'Please enter your username.', 'wp-discussion-board' ) );
				}
			//	if( $user_first == '' ) {
					// empty first name
			//		$this -> ctdb_errors() -> add( 'firstname_empty', __( 'Please enter your first name.', 'wp-discussion-board' ) );
			//	}
			//	if( $user_last == '' ) {
					// empty first name
			//		$this -> ctdb_errors() -> add( 'lastname_empty', __( 'Please enter your last name.', 'wp-discussion-board' ) );
			//	}
				if( ! is_email( $user_email ) ) {
					//invalid email
					$this -> ctdb_errors() -> add( 'email_invalid', __( 'Please check you have entered a valid email address.', 'wp-discussion-board' ) );
				}
				if( email_exists( $user_email ) ) {
					//Email address already registered
					$this -> ctdb_errors() -> add( 'email_used', __( 'That email address has already been registered.', 'wp-discussion-board' ) );
				}
				if( $user_pass == '' ) {
					// password is empty
					$this -> ctdb_errors() -> add( 'password_empty', __( 'Please enter a password.', 'wp-discussion-board' ) );
				}
				if( $user_pass != $pass_confirm ) {
					// passwords do not match
					$this -> ctdb_errors() -> add( 'password_mismatch', __( 'Please check that the passwords match.', 'wp-discussion-board' ) );
				}
		 
				$errors = $this -> ctdb_errors() -> get_error_messages();
		 
				// Only create the user if there are no errors
				if( empty( $errors ) ) {
					
					$user_options = get_option( 'ctdb_user_settings' );
					$require_activation = isset( $user_options['require_activation'] );
					
					// Define role for new users
					
					if( $require_activation ) {
						// Set the user role to pending if we require user activation
						$register_role = 'pending';
					} else if( ! empty( $options['new_user_role'] ) ) {
						$register_role = $options['new_user_role'];
					} else {
						$register_role = 'subscriber';
					}
					
					// Check what page has been set to redirect to
					/*
					$options_settings = get_option( 'ctdb_options_settings' );
					if( ! empty( $options_settings['redirect_to_page'] ) ) {
						$redirect_to = $options_settings['redirect_to_page'];
					} else {
						$redirect_to = '';
					} */
					
					// Register user without activation
					$new_user_id = wp_insert_user( array(
							'user_login'		=> $user_login,
							'user_pass'	 		=> $user_pass,
							'user_email'		=> $user_email,
							'first_name'		=> $user_first,
							'last_name'			=> $user_last,
							'user_registered'	=> date('Y-m-d H:i:s'),
							'role'				=> $register_role,
							'user_url'			=> esc_url( $website ),
							'description'		=> sanitize_text_field( $bio )
						)
					);
						
					if( $new_user_id ) {
						
						// Send an email to the admin alerting them of the registration
						wp_new_user_notification( $new_user_id );
						
						// If we require new users to activate their accounts
						if( $require_activation ) {
							
							// Add an activation key as usermeta
							$key = substr( md5( time() . rand() ), 0, 16 );
							add_user_meta( 
								$new_user_id,
								'activate_key',
								$key
							);
							
							// Email the user
							// Set HTML content type
							add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );

							$to = $user_email;
							$subject = get_bloginfo( 'name' ) . ': ' . __( 'Activate your account', 'wp-discussion-board' );
							$message = '<p>' . __( 'Thank you for registering. Please activate your account by clicking the link below:', 'wp-discussion-board' ) . '</p>';
							
							// Get the current URL and append the activation code and user ID
							$protocol =( is_ssl() ) ? 'https://' : 'http://';
							
							// Ensure we remove any parameters from the current URL
							$uri = $_SERVER["REQUEST_URI"];
							$uri = explode( '?', $uri );
							$uri = $uri[0];
							
							$url = $protocol . $_SERVER["HTTP_HOST"] . $uri . '?activate_code=' . $key . '&user_id=' . $new_user_id;
							
							$message .= '<p><a href="' . $url . '">' . $url . '</a></p>';
							
							// Set HTML content type
							add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );

							wp_mail( $to, $subject, $message );
							
							// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
							remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
						
						}
						
						add_filter( 'the_content', array( $this, 'registration_success' ) );
						
						/* else if( $user_options['auto_log_in'] ) {
						
							// Log the new user in
							wp_setcookie( $user_login, $user_pass, true );
							wp_set_current_user( $new_user_id, $user_login );	
							do_action( 'wp_login', $user_login );
							
						} */
		 
						// Send the newly created user to the correct page
						// Don't redirect after registering
						// @since 2.0.0
						/*
						if( $redirect_to ) {
							wp_redirect( get_permalink( $redirect_to ) );
							exit;
						} */
						
					}

				}
		 
			}
			
		}
		
		/*
		 * Checks for user activation
		 * @since 1.0.0
		 */
		public function activate_new_user() {
			
			if( ! isset( $_GET['activate_code'] ) || ! isset( $_GET['user_id'] ) ) {
				return;
			}
			if( $_GET['activate_code'] && $_GET['user_id'] ) {
				
				// An activation link has been clicked
				$user_id = intval( $_GET['user_id'] );
				
				// Get the activation code of the user ID in the URL
				$user_activation = get_user_meta( $user_id, 'activate_key', true );
				
				$is_pending = false;
				$user = new WP_User( $user_id );
				foreach( $user -> roles as $role ) {
					if( $role == 'pending' ) {
						$is_pending = true;
					}
				}

				// Only update if role is still pending
				if( $is_pending && $_GET['activate_code'] == $user_activation ) {
				
					// The user ID and activation code match so we'll register this user
					$options = get_option( 'ctdb_user_settings' );
					$role = $options['new_user_role'];
					
					$user_update = wp_update_user( 
						array(
							'ID'	=>	$user_id,
							'role'	=>	$role
						)
					);
					
					if( ! is_wp_error( $user_update ) ) {
						add_filter( 'the_content', array( $this, 'activate_success' ) );
					}
					
				}
			}
			
		}
		
		/*
		 * Successful registration
		 * @since 1.0.0
		 */
		public function registration_success( $content ) {
			
			$user_options = get_option( 'ctdb_user_settings' );
			$require_activation = isset( $user_options['require_activation'] );
			
			// Additional text if activation is required
			if( $require_activation ) {
				$success = '<p class="ctdb-success">' . __( 'Please check your inbox for an activation email - if you don\'t see an email from us, please remember to check your spam folder.', 'wp-discussion-board' ) . '</p>';
			} else {
				$success = '<p class="ctdb-success">' . __( 'Thank you for registering. You may now log in.', 'wp-discussion-board' ) .  '</p>';
			}
			
			// Place the message at the start of the content
			$content = $success . $content;
			
			return $content;
		}
		
		/*
		 * Successful activation from link
		 * @since 1.0.0
		 */
		public function activate_success( $content ) {
			$success = '<p class="ctdb-success">' . __( 'You have activated your account successfully. You can now log in.', 'wp-discussion-board' ) . '</p>';
			$content = $success . $content;
			return $content;
		}
		
		/*
		 * Redirect to log-in form if failed log-in
		 * @since 1.0.0
		 */
		public function redirect_to_login_page() {
			
			global $pagenow;
			$options = get_option( 'ctdb_options_settings' );
			
			// Removed @since 2.0.0
			/*
			if( ! isset( $_GET['activate_code'] ) || ! isset( $_GET['user_id'] ) ) {
				return;
			} */
			
			// Check we're not trying to log out or recover password
			if( isset( $_GET['action'] ) &&( $_GET['action'] == 'logout' || $_GET['action'] == 'lostpassword' ) ) return;
			
			// If we're heading towards wp-login.php and our settings are right
			if( 'wp-login.php' == $pagenow && $options['hide_wp_login'] && $options['frontend_login_page'] ) {
				$redirect_url = get_permalink( $options['frontend_login_page'] );
				wp_redirect( esc_url( $redirect_url ) . '/?login=bounced' ); 
				exit;
			}

		}
			
		/*
		 * Redirect to front end log-in form if failed log-in
		 * @since 1.0.0
		 * @https://pippinsplugins.com/redirect-to-custom-login-page-on-failed-login/
		 */
		public function login_fail( $username ) {
			// If we've opted to hide the wp-login.php page and have an alternative front-end page set
			$options = get_option( 'ctdb_options_settings' );
			if( isset( $options['hide_wp_login'] ) && isset( $options['frontend_login_page'] ) ) {
				// Where did the post submission come from?
				$referrer = $_SERVER['HTTP_REFERER'];  		
				// If there's a valid referrer, and it's not the default log-in screen
				if( ! empty( $referrer ) && ! strstr( $referrer, 'wp-login' ) && ! strstr( $referrer, 'wp-admin' ) ) {
					$redirect_url = get_permalink( $options['frontend_login_page'] );
					wp_redirect( esc_url( $redirect_url ) . '/?login=failed' ); 
					exit;			
				}				
			}
		}
		
		/*
		 * Tracks error messages
		 * @since 1.0.0
		 * @credit https://pippinsplugins.com/creating-custom-front-end-registration-and-login-forms/
		 */
		public function ctdb_errors() {
			static $wp_error;
			return isset( $wp_error ) ? $wp_error :( $wp_error = new WP_Error( null, null, null ) );
		}
		
		/*
		 * Displays error messages
		 * @since 1.0.0
		 * @credit https://pippinsplugins.com/creating-custom-front-end-registration-and-login-forms/
		 */
		public function ctdb_show_error_messages() {
			
			if( $codes = $this -> ctdb_errors() -> get_error_codes() ) {
				
				echo '<div class="ctdb-errors">';
					// Loop error codes and display errors
				   foreach( $codes as $code ) {
						$message = $this -> ctdb_errors() -> get_error_message( $code );
						echo '<span class="error"><strong>' . __( 'Error', 'wp-discussion-board' ) . '</strong>: ' . $message . '</span><br/>';
					}
				echo '</div>';
				
			}

		}
		
		public function set_html_content_type() {
			return 'text/html';
		}

	}
	
}