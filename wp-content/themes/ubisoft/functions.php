<?php
/**
 * ubisoft functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ubisoft
 */

add_filter('show_admin_bar', '__return_false');

if (! function_exists('ubisoft_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function ubisoft_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on ubisoft, use a find and replace
         * to change 'ubisoft' to the name of your theme in all the template files.
         */
        //load_theme_textdomain('ubisoft', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'menu-1' => esc_html__('Primary', 'ubisoft'),
            'menu-2' => esc_html__('Footer', 'ubisoft'),
            'menu-3' => esc_html__('Meta', 'ubisoft'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'gallery',
            'caption',
        ));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        // Add custom image sizes
        add_image_size('large-portrait', 800, 1000, true);
        add_image_size('large-landscape', 1000, 820, true);
        add_image_size('small-landscape', 600, 400, true);
    }
endif;
add_action('after_setup_theme', 'ubisoft_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ubisoft_content_width()
{
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters('ubisoft_content_width', 1200);
}
add_action('after_setup_theme', 'ubisoft_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ubisoft_widgets_init()
{
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'ubisoft'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'ubisoft'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'ubisoft_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function ubisoft_scripts()
{
    wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js?render='. RECAPTCHA_SITE_KEY, array(), '', true);
    wp_enqueue_script('ubisoft-uplaysdk', 'https://ubistatic2-a.akamaihd.net/uplay-connect/v3/prod/default/sdk/connectSdkPublic.js', array(), '', true);
    wp_enqueue_script('ubisoft-easyxdm', 'https://static2.cdn.ubi.com/uplay-connect/v2/live/default/js/easyXDM.min.js', array(), '', true);

    wp_enqueue_script('ubisoft-navigation', get_template_directory_uri() . '/js/navigation.js', array(),ASSETS_VERSION, true);
    wp_enqueue_script('ubisoft-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), ASSETS_VERSION, true);
    wp_enqueue_script('jwplayer', 'https://cdn.jwplayer.com/libraries/jbvflGXp.js', []);
    wp_enqueue_script('ubisoft-scripts', get_template_directory_uri() . '/assets/site.js', array(), ASSETS_VERSION, true);
    wp_enqueue_style('js_composer_front');

    if ( is_page_template( 'template-parts/scroll-of-fame.php' ) ) {
        wp_enqueue_script( 'ubisoft-scrolloffame', get_template_directory_uri() . '/assets/scroll-of-fame.js', array(), ASSETS_VERSION, true);
    }

    // Polls
    wp_enqueue_script('poll-voting-script', get_template_directory_uri() . '/assets/poll-voting.js', ['jquery'], ASSETS_VERSION, true);
    wp_localize_script('poll-voting-script', 'pollData', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('vote_nonce'), 
    ]);

    global $sitepress;

    if($sitepress){
        wp_localize_script(
            'ubisoft-scripts',
            'wp_localized',
            [
                'clear' => __('Clear', 'ubisoft'),
                'apply' => __('Apply', 'ubisoft'),
                'api_url' => admin_url('admin-ajax.php'),
                'lang' => $sitepress->get_current_language()
            ]
        );
    }

    wp_enqueue_script('comment-reply');
}
add_action('wp_enqueue_scripts', 'ubisoft_scripts');

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * ACF fields for this theme.
 */
require get_template_directory() . '/inc/custom-fields.php';

/**
 * Visual Compsoer setup for this theme.
 */
require get_template_directory() . '/inc/visual-composer.php';


/**
 * Poll voting
 */
require get_template_directory() . '/inc/poll-voting.php';

/**
 * Cron
 */
require get_template_directory() . '/inc/cron.php';

/**
 * Customizer additions.
 */
//require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Add ACF options page.
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' 	=> 'Theme General Settings',
        'menu_title'	=> 'Ubisoft Settings',
        'menu_slug' 	=> 'theme-general-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));
}

/**
 * Get next posts page link of specified page number
 */
function next_posts_link_of_page($label, $current_page = 0)
{
    global $paged, $wp_query;

    $max_page = $wp_query->max_num_pages;

    if (!$current_page) {
        $current_page = !$paged ? 1 : $paged;
    }

    $nextpage = intval($current_page) + 1;

    if ( null === $label ) {
        $label = __( 'Next Page &raquo;' );
    }

    if ( ! is_single() && ( $nextpage <= $max_page ) ) {
        /**
         * Filters the anchor tag attributes for the next posts page link.
         *
         * @since 2.7.0
         *
         * @param string $attributes Attributes for the anchor tag.
         */
        $attr = apply_filters( 'next_posts_link_attributes', '' );

        $next_page_link = get_pagenum_link( $nextpage );
        $next_page_link = preg_replace('/lmpage=(\d+)/', '', $next_page_link);
        $next_page_link = str_replace('?&', '?', $next_page_link);
        $next_page_link = preg_replace('/\?$/', '', $next_page_link);

        echo '<a href="' . $next_page_link . '" ' . $attr . '><span>' . preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label ) . '</span></a>';
    }
}

function my_theme_posts_link_attributes()
{
    return 'class="button dark"';
}

add_filter('next_posts_link_attributes', 'my_theme_posts_link_attributes');

/**
 * Press filter vars
 */
function add_press_query_vars( $vars ) {
    $vars[] = 'lmpage';
    $vars[] = 'relevance';
    $vars[] = 'datespan';
    return $vars;
}
add_filter( 'query_vars', 'add_press_query_vars' );

function set_press_filters($query)
{
    if (!is_admin() && is_home() && $query->is_main_query()) {
        if (!empty(get_query_var('lmpage'))) {
            $default_posts_per_page = get_option('posts_per_page') * intval(get_query_var('lmpage'));
            $query->set('posts_per_page', $default_posts_per_page);
        }

        if (!empty(get_query_var('relevance'))) {
            $query->set('cat', intval(get_query_var('relevance')));
        }

        if (!empty(get_query_var('datespan'))) {
            $datespan = explode('t', get_query_var('datespan'));
            if (2 === count($datespan)) {
                $query->set('date_query', [
                    [
                        'after'     => $datespan[0],
                        'before'    => $datespan[1],
                        'inclusive' => true
                    ]
                ]);
            }
        }
    }
}
add_action('pre_get_posts', 'set_press_filters');

/**
 * Related Press API
 */
require get_template_directory() . '/inc/related-press.php';

add_post_type_support( 'updates', 'thumbnail' );
add_post_type_support( 'games', 'thumbnail' );
add_post_type_support( 'videos', 'thumbnail' );
function create_posttype() {
    register_post_type( 'videos',
        array(
            'labels' => array(
                'name' => __( 'Videos' ),
                'singular_name' => __( 'Video' )
            ),
            'publicly_queryable' => true,
            'public' => true,
            'has_archive' =>  __( 'videos' ),
            'menu_position' => 5,
            'rewrite' => array('slug' => 'videos'),
            'menu_icon'         => 'dashicons-video-alt3',
            'taxonomies'        => array(
                'category','post_tag'
            ),
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' )
        )
    );
    register_post_type( 'games',
        array(
            'labels' => array(
                'name' => __( 'Games' ),
                'singular_name' => __( 'Game' )
            ),
            'publicly_queryable' => true,
            'public' => true,
            'menu_position' => 6,
            'rewrite'           => array('slug' => 'games'),
            'menu_icon'         => 'dashicons-admin-generic',
            'taxonomies'        => array(
                'category','post_tag'
            ),
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
        )
    );
    register_post_type( 'updates',
        array(
            'labels' => array(
                'name' => __( 'Updates' ),
                'singular_name' => __( 'Update' )
            ),
            'publicly_queryable' => true,
            'public' => true,
            'menu_position'     => 7,
            'rewrite' => array('slug' => 'updates'),
            'menu_icon'         => 'dashicons-admin-site-alt3',
            'taxonomies'        => array(
                'category','post_tag'
            ),
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' )
        )
    );
    register_post_type( 'events',
        array(
            'labels' => array(
                'name' => __( 'Events' ),
                'singular_name' => __( 'Event' )
            ),
            'publicly_queryable' => true,
            'public' => true,
            'menu_position'     => 7,
            'rewrite' => array('slug' => 'events'),
            'menu_icon'         => 'dashicons-admin-site-alt3',
            'taxonomies'        => array(
                'category','post_tag'
            ),
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' )
        )
    );
    // register_post_type( 'voting',
    //     array(
    //         'labels' => array(
    //             'name' => __( 'Votings' ),
    //             'singular_name' => __( 'Voting' )
    //         ),
    //         'publicly_queryable' => true,
    //         'public' => true,
    //         'has_archive' => true,
    //         'menu_position'     => 7,
    //         'rewrite' => array('slug' => 'voting'),
    //         'menu_icon'         => 'dashicons-admin-site-alt3',
    //         'taxonomies'        => array(
    //             'category','post_tag'
    //         ),
    //         'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' )
    //     )
    // );
    register_post_type( 'newscategories',
        array(
            'labels' => array(
                'name' => __( 'News Categories' ),
                'singular_name' => __( 'News Category' )
            ),
            'publicly_queryable' => true,
            'public' => true,
            'has_archive' => true,
            'menu_position'     => 7,
            'rewrite' => array('slug' => 'newscategories'),
            'menu_icon'         => 'dashicons-admin-site-alt3',
            'taxonomies'        => array(
                'category','post_tag'
            ),
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' )
        )
    );
    register_post_type('polls', [
        'labels' => [
            'name' => 'Polls',
            'singular_name' => 'Poll',
        ],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-megaphone'
    ]);

    flush_rewrite_rules();

}

add_action( 'init', 'create_posttype' );


if ( ! function_exists( ( 'customize_comments' ) ) ) {
	function customize_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
        $author = $comment->user_id ? get_userdata( $comment->user_id ) : false;
        $author_role = ($author && is_array($author->roles)?array_shift($author->roles):"");
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment <?php if($author_role == "administrator"): ?>ubisoft-comment<?php endif; ?>">
			<div class="comment-author">
                <?php if($author_role == "administrator"): ?>
                    <span class="ubisoft-avatar">&nbsp;</span>
                <?php else: ?>
				<span class="initial">
                <?php echo $comment->comment_author[0]; ?></span>
                <?php endif; ?>
				<span class="author-name"><?= $comment->comment_author?></span>
                <span class="comment-date">
                    <?php
                    $formatter = new IntlDateFormatter(ICL_LANGUAGE_CODE, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                    $dateTime = new DateTime(get_comment_date());
                    echo $formatter->format($dateTime);
                    ?>
                </span>
			</div>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'emptyspace' ) ?></em>
					<br/>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<div class="comment-footer">
				<?php comment_reply_link( array_merge( $args, array(
					'reply_text' => _x( 'Reply', 'verb: reply to this comment', 'emptyspace' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth']
				) ) ); ?>
				<?php edit_comment_link( _x( 'Edit', 'verb: edit this comment', 'emptyspace' ) ); ?>
			</div>
		</article>
		<?php
	}
}


global $sitepress;
remove_filter('comments_clauses', array( $sitepress, 'comments_clauses' ), 10);
// add_filter('pre_get_comments', 'my_pre_get_comments', 100, 1);

function my_pre_get_comments(\WP_Comment_Query &$wp_comment_query)
{
    if (($postId = $wp_comment_query->query_vars['post_id'])) {
        $postIds = [$postId];

        // get all languages
        $languages = apply_filters('wpml_active_languages', null, 'skip_missing=1');
        $type = get_post_type($postId);
        foreach($languages as $l) {
            if(!$l['active']) {
                $otherId = apply_filters('wpml_object_id', $postId, $type, false, $l['language_code']);
                if ($otherId) {
                    $postIds[] = $otherId;
                }
            }
        }

        // don't query for a specific post, instead query all language posts
        $wp_comment_query->query_vars['post_id'] = '';
        $wp_comment_query->query_vars['post_ID'] = '';
        $wp_comment_query->query_vars['post__in'] = $postIds;
    }
}

// Remove Gutenberg Block Library CSS from loading on the frontend
function wp_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
}
add_action('wp_enqueue_scripts', 'wp_remove_wp_block_library_css', 100);

// Remove jQuery from frontend
/*
function remove_jquery_migrate(){
    if ( !is_admin() ) {
        wp_deregister_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'remove_jquery_migrate', 100);
*/

// Remove WP Rest's silly empty CSS file
// wp-rest-filter-public.css
function wp_remove_rest_css(){
    wp_dequeue_style( 'wp-rest-filter' );
}
add_action('wp_enqueue_scripts', 'wp_remove_rest_css', 100);

// Remove WPML's unneeded CSS
function wp_wpml_remove_default_stylesheet() {
    wp_dequeue_style( 'wpml-legacy-vertical-list-0' );
}
add_action('wp_enqueue_scripts', 'wp_wpml_remove_default_stylesheet', 100);

/**
 * Set comment form defaults
 */
add_filter('comment_form_defaults', function($defaults) {
    $user = wp_get_current_user();
    $user_identity = $user->exists() ? $user->display_name : '';
    $redirectId = 750;
    $currentLanguage = ICL_LANGUAGE_CODE;
    if($currentLanguage == 'en'){
        $redirectId = 747;
    }

    $required_attribute = ' required';
    $checked_attribute  = ' checked';
    $required_indicator = ' ' . wp_required_field_indicator();
    $required_text      = ' ' . wp_required_field_message();

    $defaults['title_reply'] = ($currentLanguage === 'de' ? 'Kommentar hinterlassen' : 'Leave a Reply');
    $defaults['title_reply_before'] = '<h2 id="reply-title" class="h2 smaller comment-reply-title">';
    $defaults['title_reply_after'] = '</h2>';

    $defaults['must_log_in'] = '<p class="must-log-in text-larger">' . sprintf(
        /* translators: %s: login URL */
            ($currentLanguage === 'de' ? '<a href="%s">Einloggen zum Kommentieren</a>.' : '<a href="%s">Log in to post a comment</a>.'),
            get_page_link($redirectId)
        ) . '</p>';

    $defaults['logged_in_as'] = '<p class="logged-in-as text-larger">' . sprintf(
        /* translators: 2: username, 3: logout URL */
            ($currentLanguage === 'de' ? 'Eingeloggt als %1$s. <a href="%2$s" class="logout-btn">Ausloggen?</a>' : 'Logged in as %1$s. <a href="%2$s" class="logout-btn">Log out?</a>'),
            $user_identity,
            get_page_link($redirectId)
        ) . '</p>';

    $defaults['comment_field'] = sprintf(
        '<p class="comment-form-comment">%s %s</p>',
        sprintf(
            '<label for="comment">%s%s</label>',
            ($currentLanguage === 'de' ? 'Kommentieren' : 'Comment'),
            $required_indicator
        ),
        '<textarea id="comment" name="comment" cols="45" rows="8" placeholder="' . (ICL_LANGUAGE_CODE === 'de' ? 'Kommentar eingeben ...' : 'Type your message ...') . '" maxlength="65525"' . $required_attribute . '></textarea>'
    );

    $defaults['label_submit'] = ($currentLanguage === 'de' ? 'Kommentar speichern' : 'Post Comment');

    $defaults['cancel_reply_link'] = ($currentLanguage === 'de' ? 'Abbrechen' : 'Cancel');

    return $defaults;
});

add_filter('comment_reply_links', function($content) {
    return str_replace('Reply', (ICL_LANGUAGE_CODE === 'de' ? 'Antworten' : 'Reply'), $content);
});

add_filter('edit_comment_link', function($link) {
    return str_replace('Edit', (ICL_LANGUAGE_CODE === 'de' ? 'Bearbeiten' : 'Edit'), $link);
}, 10, 3 );

/**
 * Google recaptcha add before the submit button
 */
function add_google_recaptcha($submit_field) {
    $submit_field['submit_field'] = '<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                                     <input type="hidden" name="action" value="validate_captcha">
                                     <input name="buttonSubmit" type="submit" id="buttonSubmit" class="submit" value="Post Comment" />
                                     <input type="hidden" name="comment_post_ID" value="'. get_the_id() . '" id="comment_post_ID" />
                                     <input type="hidden" name="comment_parent" id="comment_parent" value="0" />
                                     <script>
                                     document.getElementById("buttonSubmit").onclick = function onClick(e) {
                                        e.preventDefault();
                                        grecaptcha.ready(function() {
                                          grecaptcha.execute("'.RECAPTCHA_SITE_KEY.'", {action: "submit"}).then(function(token) {
                                            document.getElementById("g-recaptcha-response").value = token;
                                            document.getElementById("commentform").submit();
                                          });
                                        });
                                    }
                                    </script>';
    return $submit_field;
}
if (!is_user_logged_in()) {
    add_filter('comment_form_defaults','add_google_recaptcha');
}

/**
 * Google recaptcha check, validate and catch the spammer
 */

function is_valid_captcha($captcha) {
    $captcha_postdata = http_build_query(array(
                            'secret'   => RECAPTCHA_SECRET_KEY,
                            'response' => $captcha,
                            'remoteip' => $_SERVER['REMOTE_ADDR']));

    $captcha_opts = array('http' => array(
                      'method'  => 'POST',
                      'header'  => 'Content-type: application/x-www-form-urlencoded',
                      'content' => $captcha_postdata));

    $captcha_context  = stream_context_create($captcha_opts);
    $captcha_response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify" , false , $captcha_context), true);

    if ($captcha_response['success'] && $captcha_response['score'] > 0.5){
        return true;
    }else{
        return false;
    }

}

function verify_google_recaptcha() {
    $recaptcha = $_POST['g-recaptcha-response'];
    if (empty($recaptcha)){
        wp_die(__("<b>ERROR:</b><b>Sorry, spam detected</b>"));
    }elseif (!is_valid_captcha($recaptcha)){
        wp_die(__("<b>Sorry, spam detected!</b>"));
    }
}

/**
 * Allow SVG upload.
 */
add_filter('upload_mimes', function ($allowed) {
    if (!current_user_can('manage_options')) {
        return $allowed;
    }
    $allowed['svg'] = 'image/svg+xml';
    return $allowed;
});

/**
 * Stop WordPress from scaling big images.
 */
add_filter( 'big_image_size_threshold', '__return_false' );




// =================================================================================
// DEV Console
// =================================================================================

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
} 

function blend_script_enqueuer() {
	if ( ! is_admin() ) {

        // FontAwesome
        wp_register_script( 'blend_font_awesome', 'https://kit.fontawesome.com/25514dd693.js', array( 'jquery' ), null, true );
        wp_enqueue_script( 'blend_font_awesome' );
		
	};
}
add_action( 'wp_enqueue_scripts', 'blend_script_enqueuer' );