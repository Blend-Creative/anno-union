<?php
/* If a post password is required or no comments are given and comments/pings are closed, return. */
if ( post_password_required() || ( ! have_comments() && ! comments_open() && ! pings_open() ) ) {
	return;
}
$currentLanguage = ICL_LANGUAGE_CODE;
$current_user = wp_get_current_user();

$user_isBanned = false;
if(!empty($current_user)){
    $user_id = $current_user->ID;
    $key = 'banned';
    $single = true;
    $user_banned = get_user_meta( $user_id, $key, $single );
    if($user_banned == 1){
	    $user_isBanned = true;
    }
	$key = 'banned_date';
	$single = true;
	$user_bannedDate = get_user_meta( $user_id, $key, $single );

	$tsTime = time();
	if(!empty($user_bannedDate)){
		$bannedDateStamp = strtotime( $user_bannedDate );
		if($tsTime > $bannedDateStamp){
			$user_isBanned = true;
        };
    };

    $key = 'unbanned_date';
    $single = true;
    $user_unbannedDate = get_user_meta( $user_id, $key, $single );

	if(!empty($user_unbannedDate)){
		$unbannedDateStamp = strtotime( $user_unbannedDate );

		if($tsTime > $unbannedDateStamp){
			$user_isBanned = false;
		} else {
			$user_isBanned = true;
        }
	};
}

if ( comments_open()) { ?>
    <div class="wpb-content-wrapper" id="comments">
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-md-6 vc_col-lg-5 comments-form-column">
                <div class="wpb_text_column wpb_content_element">
                    <p><strong><?php echo $currentLanguage === 'de' ? 'Kommentare' : 'Comments'; ?></strong></p>
                    <?php if($user_isBanned === false) : ?>
                        <?php comment_form(); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="wpb_column vc_column_container vc_col-sm-1 vc_hidden-sm vc_hidden-xs"></div>
            <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-md-6">
                <div class="wpb_text_column wpb_content_element">
                    <div class="vc_hidden-sm vc_hidden-xs">
                        <p><strong>&nbsp;</strong></p>
                        <h2 class="h2 smaller comment-reply-title">
                            &nbsp;
                            <?php
                            if ($currentLanguage == 'de') {
                                echo '<br />&nbsp;';
                            }
                            ?>
                        </h2>
                    </div>
                    <div class="comments-meta">
                        <?php
                        if ($currentLanguage == 'en') {
                            comments_number( __( 'No comments yet.' ), __( '1 Comment' ), _x( '% Comments', 'noun: 5 comments' ) );
                        } else {
                            comments_number( __( 'Noch keine Kommentare.' ), __( '1 Kommentar' ), _x( '% Kommentare', 'noun: 5 Kommentare' ) );
                        }
                        ?>
                    </div>
                    <div class="comments">
                        <ol class="comment-list">
                            <?php wp_list_comments( array( 'walker' => new ubisoft_walker_comment, 'callback' => 'customize_comments' ) ); ?>
                        </ol>
                        <?php
                        if ( ( get_option( 'page_comments' ) == 1 ) && ( get_comment_pages_count() > 1 ) ) { ?>
                            <nav class="comment-pagination">
                                <p class="previous-comment"><?php previous_comments_link(); ?></p>
                                <p class="next-comment"><?php next_comments_link(); ?></p>
                            </nav>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} elseif ( ! comments_open() && have_comments() && pings_open() ) { ?>
    <div class="wpb-content-wrapper" id="comments">
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-md-6 vc_col-lg-5 comments-form-column">
                <div class="wpb_text_column wpb_content_element">
                    <p><strong><?php echo $currentLanguage === 'de' ? 'Kommentare' : 'Comments'; ?></strong></p>
                    <h2 class="h4 smaller">
                        <?php
                        if ($currentLanguage == 'de') {
                            printf( __( 'Kommentare sind geschlossen, aber <a href="%s" title="Trackback URL for this post">Trackbacks</a> and Pingbacks sind offen.' ), esc_url( get_trackback_url() ) );
                        } else {
                            printf( __( 'Comments are closed, but <a href="%s" title="Trackback URL for this post">trackbacks</a> and pingbacks are open.' ), esc_url( get_trackback_url() ) );
                        }
                        ?>
                    </h2>
                </div>
            </div>
            <div class="wpb_column vc_column_container vc_col-sm-1 vc_hidden-sm vc_hidden-xs"></div>
            <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-md-6">
                <div class="wpb_text_column wpb_content_element">
                    <div class="vc_hidden-sm vc_hidden-xs">
                        <p><strong>&nbsp;</strong></p>
                    </div>
                    <div class="comments">
                        <ol class="comment-list">
                            <?php wp_list_comments( array( 'walker' => new ubisoft_walker_comment, 'callback' => 'customize_comments' ) ); ?>
                        </ol>
                        <?php
                        if ( ( get_option( 'page_comments' ) == 1 ) && ( get_comment_pages_count() > 1 ) ) { ?>
                            <nav class="comment-pagination">
                                <p class="previous-comment"><?php previous_comments_link(); ?></p>
                                <p class="next-comment"><?php next_comments_link(); ?></p>
                            </nav>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
} elseif ( ! comments_open() && have_comments() ) { ?>
    <div class="wpb-content-wrapper" id="comments">
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-md-6 vc_col-lg-5 comments-form-column">
                <div class="wpb_text_column wpb_content_element">
                    <p><strong><?php echo $currentLanguage === 'de' ? 'Kommentare' : 'Comments'; ?></strong></p>
                    <h2 class="h4 smaller">
                        <?php
                        if ($currentLanguage == 'de') {
                            printf( __( 'Kommentare sind geschlossen.' ), esc_url( get_trackback_url() ) );
                        } else {
                            printf( __( 'Comments are closed.' ), esc_url( get_trackback_url() ) );
                        }
                        ?>
                    </h2>
                </div>
            </div>
            <div class="wpb_column vc_column_container vc_col-sm-1 vc_hidden-sm vc_hidden-xs"></div>
            <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-md-6">
                <div class="wpb_text_column wpb_content_element">
                    <div class="vc_hidden-sm vc_hidden-xs">
                        <p><strong>&nbsp;</strong></p>
                    </div>
                    <div class="comments">
                        <ol class="comment-list">
                            <?php wp_list_comments( array( 'walker' => new ubisoft_walker_comment, 'callback' => 'customize_comments' ) ); ?>
                        </ol>
                        <?php
                        if ( ( get_option( 'page_comments' ) == 1 ) && ( get_comment_pages_count() > 1 ) ) { ?>
                            <nav class="comment-pagination">
                                <p class="previous-comment"><?php previous_comments_link(); ?></p>
                                <p class="next-comment"><?php next_comments_link(); ?></p>
                            </nav>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
} else {
	$output = true;

	// don't output on WooCommerce pages like Cart and Checkout
	if ( function_exists( 'is_woocommerce' ) ) {
		if ( is_cart() || is_checkout() || is_account_page() ) {
			$output = false;
		}
	}
	if ( $output ) { ?>
        <div class="wpb-content-wrapper" id="comments">
            <div class="vc_row wpb_row vc_row-fluid">
                <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-md-6 vc_col-lg-5 comments-form-column">
                    <div class="wpb_text_column wpb_content_element">
                        <p><strong><?php echo $currentLanguage === 'de' ? 'Kommentare' : 'Comments'; ?></strong></p>
                        <h2 class="h4 smaller">
                            <?php
                            if ($currentLanguage == 'de') {
                                printf( __( 'Kommentare sind geschlossen.' ), esc_url( get_trackback_url() ) );
                            } else {
                                printf( __( 'Comments are closed.' ), esc_url( get_trackback_url() ) );
                            }
                            ?>
                        </h2>
                    </div>
                </div>
                <div class="wpb_column vc_column_container vc_col-sm-1 vc_hidden-sm vc_hidden-xs">
                    <br /><br /><br /><br /><br /><br /><br /><br />
                </div>
                <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-md-6">

                </div>
            </div>
        </div>
	<?php }
}

class ubisoft_walker_comment extends Walker_Comment {
    public $tree_type = 'comment';
    public $db_fields = array ('parent' => 'comment_parent', 'id' => 'comment_ID');
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1;
        $morelink = 'Antworten zum Kommentar anzeigen';
        $closelink = 'Antworten zum Kommentar verbergen';
        if(function_exists(ICL_LANGUAGE_CODE)){
            $currentLanguage = ICL_LANGUAGE_CODE('slug');
            if($currentLanguage == 'en'){
                $morelink = 'Show answers to the comment';
                $closelink = 'Hide answers to the comment';
            }
        }

        switch ( $args['style'] ) {
            case 'div':
                break;
            case 'ol':
                $output .= '<ol class="children">' . "\n";
                break;
            case 'ul':
            default:
                $output .= '<ul class="children">' . "\n";
                break;
        }
    }
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1;
        switch ( $args['style'] ) {
            case 'div':
                break;
            case 'ol':
                $output .= "</ol>\n";
                break;
            case 'ul':
            default:
                $output .= "</ul>\n";
                break;
        }
    }
    public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( !$element )
            return;
        $id_field = $this->db_fields['id'];
        $id = $element->$id_field;
        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
        /*
         * If at the max depth, and the current element still has children, loop over those
         * and display them at this level. This is to prevent them being orphaned to the end
         * of the list.
         */
        if ( $max_depth <= $depth + 1 && isset( $children_elements[$id]) ) {
            foreach ( $children_elements[ $id ] as $child )
                $this->display_element( $child, $children_elements, $max_depth, $depth, $args, $output );
            unset( $children_elements[ $id ] );
        }
    }
    public function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment;
        if ( !empty( $args['callback'] ) ) {
            ob_start();
            call_user_func( $args['callback'], $comment, $args, $depth );
            $output .= ob_get_clean();
            return;
        }
        if ( ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) && $args['short_ping'] ) {
            ob_start();
            $this->ping( $comment, $depth, $args );
            $output .= ob_get_clean();
        } elseif ( 'html5' === $args['format'] ) {
            ob_start();
            $this->html5_comment( $comment, $depth, $args );
            $output .= ob_get_clean();
        } else {
            ob_start();
            $this->comment( $comment, $depth, $args );
            $output .= ob_get_clean();
        }
    }
    public function end_el( &$output, $comment, $depth = 0, $args = array() ) {
        if ( !empty( $args['end-callback'] ) ) {
            ob_start();
            call_user_func( $args['end-callback'], $comment, $args, $depth );
            $output .= ob_get_clean();
            return;
        }
        if ( 'div' == $args['style'] )
            $output .= "</div><!-- #comment-## -->\n";
        else
            $output .= "</li><!-- #comment-## -->\n";
    }
    protected function ping( $comment, $depth, $args ) {
        $tag = ( 'div' == $args['style'] ) ? 'div' : 'li';
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( '', $comment ); ?>>
        <div class="comment-body">
            <?php _e( 'Pingback:' ); ?> <?php comment_author_link( $comment ); ?> <?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
        </div>
        <?php
    }

    protected function comment( $comment, $depth, $args ) {
        if ( 'div' == $args['style'] ) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo $tag; ?> <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?> id="comment-<?php comment_ID(); ?>">
        <?php if ( 'div' != $args['style'] ) : ?>
            <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
        <?php endif; ?>
        <div class="comment-author vcard">
            <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
        </div>
        <?php if ( '0' == $comment->comment_approved ) : ?>
            <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ) ?></em>
            <br />
        <?php endif; ?>

        <div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                <?php
                /* translators: 1: comment date, 2: comment time */
                printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '&nbsp;&nbsp;', '' );
            ?>
        </div>

        <?php comment_text( $comment, array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>

        <?php
        comment_reply_link( array_merge( $args, array(
            'add_below' => $add_below,
            'depth'     => $depth,
            'max_depth' => $args['max_depth'],
            'before'    => '<div class="reply">',
            'after'     => '</div>'
        ) ) );
        ?>

        <?php if ( 'div' != $args['style'] ) : ?>
            </div>
        <?php endif; ?>
        <?php
    }
    protected function html5_comment( $comment, $depth, $args ) {
        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
                    <?php printf( __( '%s <span class="says">says:</span>' ), sprintf( '<span class="fn">%s</span>', get_comment_author_link( $comment ) ) ); ?>
                </div><!-- .comment-author -->

                <div class="comment-metadata">




                    <?php
                    $user_id=$comment->user_id;
                    ?>
                    <p class="commenter-bio"><?php the_author_meta('description',$user_id); ?></p>
                    <p class="commenter-url"><a href="<?php the_author_meta('user_url',$user_id); ?>" target="_blank"><?php the_author_meta('user_url',$user_id); ?></a></p>

                </div><!-- .comment-metadata -->

                <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
                <?php endif; ?>
            </footer><!-- .comment-meta -->

            <div class="comment-content">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <?php
            comment_reply_link( array_merge( $args, array(
                'add_below' => 'div-comment',
                'depth'     => $depth,
                'max_depth' => $args['max_depth'],
                'before'    => '<div class="reply">',
                'after'     => '</div>'
            ) ) );
            ?>
        </article><!-- .comment-body -->
        <?php
    }}
