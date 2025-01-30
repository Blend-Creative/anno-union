<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package ubisoft
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function ubisoft_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if(isset($_COOKIE["darkmode"]) && $_COOKIE["darkmode"] ==1) {
		$classes[] = 'darkmode';
	}
	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'ubisoft_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function ubisoft_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'ubisoft_pingback_header' );

/**
 * Include social media icons in footer menu.
 */
function add_social_media_footer($items, $args) {
    if ($args->theme_location == 'menu-2'){
        $options = get_fields('option');
        if (!empty($options['social_channels_buttons'])) {
            $items .= '<li class="menu-item"><a>Follow Us</a><ul class="footer__social">';
            foreach($options['social_channels_buttons'] as $button) {
                if (!empty($button['component_button_show_footer'])) {
                    $items .= '<li><a ' . (!empty($button['social_channels_button_link']) ? 'href="' . $button['social_channels_button_link'] . '" target="_blank" rel="noopener"' : '') . '>
                        ' . (!empty($button['social_channels_button_icon']) ? '<img src="' . $button['social_channels_button_icon'] . '" alt="' . ($button['social_channels_button_title'] ?? '') . '>" />' : '') . '
                        ' . (!empty($button['social_channels_button_title']) ? '<span>' . $button['social_channels_button_title'] . '</span>' : '') . '
                    </a></li>';
                }
            }
        }
        $items .= '</ul></li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'add_social_media_footer', 10, 2);
