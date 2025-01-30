<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package ubisoft
 */

if ( ! function_exists( 'ubisoft_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function ubisoft_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'ubisoft' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'ubisoft_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function ubisoft_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'ubisoft' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'ubisoft_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function ubisoft_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'ubisoft' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'ubisoft' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'ubisoft' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'ubisoft' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'ubisoft' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'ubisoft' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'ubisoft_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function ubisoft_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail( 'post-thumbnail', array(
				'alt' => the_title_attribute( array(
					'echo' => false,
				) ),
			) );
			?>
		</a>

		<?php
		endif; // End is_singular().
	}
endif;

if ( ! class_exists('Ubisoft_Nav_Walker' )) {
    class Ubisoft_Nav_Walker extends Walker_Nav_Menu
    {
        function start_el(&$output, $data_object, $depth = 0, $args = [], $current_object_id = 0)
        {
            $output .= '<li class="' . ($data_object->classes ? implode(' ', $data_object->classes) : '') . '">';
            if ($data_object->url) {
                $output .= '<a href="' . $data_object->url . '">';
            } else {
                $output .= '<span>';
            }

            $output .= $data_object->title;

            if ($data_object->url) {
                $output .= '</a>';
            } else {
                $output .= '</span>';
            }

            if (in_array('menu-item-has-children', $data_object->classes)) {
                $output .= '<svg class="menu-item-arrow" width="14" height="8" viewBox="0 0 14 8" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.39819 7.66292C7.17851 7.88259 6.82241 7.88259 6.60274 7.66292L0.867876 1.92804C0.648208 1.70837 0.648208 1.35227 0.867876 1.13259L1.13305 0.867393C1.35271 0.647718 1.70887 0.647718 1.92854 0.867393L7.00046 5.93934L12.0724 0.867393C12.2921 0.647718 12.6482 0.647718 12.8679 0.867393L13.1331 1.13259C13.3527 1.35227 13.3527 1.70837 13.1331 1.92804L7.39819 7.66292Z" fill="currentColor"/></svg>';
            }
        }
    }
}

if ( ! class_exists('Ubisoft_Nav_Walker_Mobile' )) {
    class Ubisoft_Nav_Walker_Mobile extends Walker_Nav_Menu
    {
        function start_el(&$output, $data_object, $depth = 0, $args = [], $current_object_id = 0)
        {
            $output .= '<li class="' . ($data_object->classes ? implode(' ', $data_object->classes) : '') . '">';
            if ($data_object->url) {
                $output .= '<a href="' . $data_object->url . '">';
            } else {
                $output .= '<span>';
            }

            $output .= $data_object->title;

            if ($data_object->url) {
                $output .= '</a>';
            } else {
                $output .= '</span>';
            }

            if (in_array('menu-item-has-children', $data_object->classes)) {
                $output .= '<div class="menu-item-toggle"><svg width="14" height="8" viewBox="0 0 14 8" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.39819 7.66292C7.17851 7.88259 6.82241 7.88259 6.60274 7.66292L0.867876 1.92804C0.648208 1.70837 0.648208 1.35227 0.867876 1.13259L1.13305 0.867393C1.35271 0.647718 1.70887 0.647718 1.92854 0.867393L7.00046 5.93934L12.0724 0.867393C12.2921 0.647718 12.6482 0.647718 12.8679 0.867393L13.1331 1.13259C13.3527 1.35227 13.3527 1.70837 13.1331 1.92804L7.39819 7.66292Z" fill="currentColor"/></svg></div>';
            }
        }
    }
}
