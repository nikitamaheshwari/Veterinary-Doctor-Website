<?php
/*
 * Functions for skins
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Comment callback for classic forum
 * @since 1.7.0
 */
if ( ! function_exists ( 'ctdb_classic_forum_comment' ) ) {
	function ctdb_classic_forum_comment( $comment, $args, $depth ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li'; ?>
	        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children']) ? 'parent' : '', $comment ); ?>>
	            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
					
					<header class="comment-header">
	                    <div class="comment-metadata">
	                        <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
	                            <time class="comment-timeago" datetime="<?php comment_time( 'c' ); ?>">
	                                <?php
	                                    /* translators: 1: comment date, 2: comment time */
	                                    printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ), get_comment_time() );
	                                ?>
	                            </time>
	                        </a>
							<?php do_action( 'ctdb_comment_metadata_end' ); ?>
								
	                    </div><!-- .comment-metadata -->
						<?php edit_comment_link( __( 'Edit', 'wp-discussion-board' ), '<span class="edit-link ctdb-edit-link">', '</span>' ); ?>
					</header>
	                
					<footer class="comment-meta">
	                    
						<div class="comment-author vcard">
	                        <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	                        <?php echo get_comment_author_link( $comment ); ?>
	                    </div><!-- .comment-author -->
						
						<?php do_action( 'ctdb_comment_author_end' ); ?>
						
	                </footer><!-- .comment-meta -->

	                <div class="comment-content">
						
	                    <?php comment_text(); ?>
						
						<?php if ( '0' == $comment->comment_approved ) : ?>
							<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'wp-discussion-board' ); ?></p>
						<?php endif; ?>
						
		                <?php
		                comment_reply_link( array_merge( $args, array(
		                    'add_below' => 'div-comment',
		                    'depth'     => $depth,
		                    'max_depth' => $args['max_depth'],
		                    'before'    => '<span class="reply ctdb-reply">',
		                    'after'     => '</span>'
		                ) ) );
		                ?>
						
	                </div><!-- .comment-content -->
	                
	            </article><!-- .comment-body -->
		<?php
	}
}

/*
 * A list of fields displaying meta data for topics
 * Called in CT_DB_Admin
 * Called in CT_DB_Skins
 * Update the value, never the key
 * @returns Array
 * @since 1.7.0
 */
function ctdb_meta_data_fields() {
	$fields = array( 
		'replies' 	=> __( 'Replies', 'wp-discussion-board' ),
		'voices'	=> __( 'Voices', 'wp-discussion-board' ),
	);
	$fields = apply_filters( 'ctdb_topic_meta_data_fields', $fields );
	return $fields;
}

/*
 * Return the list of selected meta data fields
 * We use this to check what fields to display in the single topic page
 * Used in CT_DB_Skins
 * @returns Array
 * @since 1.7.0
 */
function ctdb_selected_meta_fields() {
	$options = get_option( 'ctdb_design_settings' );
	
	$meta_data_fields = array();
	if( isset( $options['meta_data_fields'] ) ) {
		$meta_data_fields = $options['meta_data_fields'];
	}

	// Check what fields are permitted, in case user disables Pro version
	$permitted_fields = ctdb_meta_data_fields();
	// Remove any fields that aren't permitted
	$meta_data_fields = array_diff( $meta_data_fields, $permitted_fields );

	return $meta_data_fields;
}