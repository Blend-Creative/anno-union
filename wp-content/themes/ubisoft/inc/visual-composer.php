<?php
/**
 * Load custom modules and templates.
 */
add_action('vc_before_init', 'vc_before_init_actions');
function vc_before_init_actions()
{
    require get_template_directory() . '/visualcomposer/vc-functions.php';

    foreach (glob(get_template_directory() . '/visualcomposer/modules/*.php') as $file) {
        require_once($file);
    }
}

/**
 * Remove all default VC elements
 */
add_action('vc_after_init', 'vc_after_init_actions');

function vc_after_init_actions()
{
    vc_remove_element('vc_zigzag');
    vc_remove_element('vc_cta');
    vc_remove_element('vc_text');
    vc_remove_element('vc_single_image');
    vc_remove_element('vc_accordion');
    vc_remove_element('vc_gmaps');
    vc_remove_element('vc_tta_accordion');
    //vc_remove_element('vc_video');
    vc_remove_element('vc_google_maps');
    vc_remove_element('vc_empty_space');
    vc_remove_element('vc_progress_bar');
    vc_remove_element('vc_pie');
    vc_remove_element('vc_round_chart');
    vc_remove_element('vc_line_chart');
    vc_remove_element('vc_separator');
    vc_remove_element('vc_gallery');
    vc_remove_element('vc_carousel');
    vc_remove_element('vc_btn');
    vc_remove_element('vc_cta');
    vc_remove_element('vc_flickr');
    vc_remove_element('vc_posts_slider');
    vc_remove_element('vc_basic_grid');
    vc_remove_element('vc_media_grid');
    vc_remove_element('vc_masonry_grid');
    vc_remove_element('vc_masonry_media_grid');
    vc_remove_element('vc_acf');
    vc_remove_element('vc_icon');
    vc_remove_element('vc_text_separator');
    vc_remove_element('vc_message');
    vc_remove_element('vc_images_carousel');
    vc_remove_element('vc_custom_heading');
    vc_remove_element('vc_toggle');
    vc_remove_element('vc_raw_html');
    vc_remove_element('vc_raw_js');
    vc_remove_element('vc_widget_sidebar');
    vc_remove_element('vc_tta_tabs');
    vc_remove_element('vc_tta_tour');
    vc_remove_element('vc_tta_pageable');
    vc_remove_element('vc_facebook');
    vc_remove_element('vc_tweetmeme');
    vc_remove_element('vc_googleplus');
    vc_remove_element('vc_pinterest');
    vc_remove_element('vc_wp_archives');
    vc_remove_element('vc_wp_calendar');
    vc_remove_element('vc_wp_categories');
    vc_remove_element('vc_wp_custommenu');
    vc_remove_element('vc_wp_links');
    vc_remove_element('vc_wp_meta');
    vc_remove_element('vc_wp_pages');
    vc_remove_element('vc_wp_posts');
    vc_remove_element('vc_wp_recentcomments');
    vc_remove_element('vc_wp_rss');
    vc_remove_element('vc_wp_search');
    vc_remove_element('vc_wp_tagcloud');
    vc_remove_element('vc_wp_text');
    vc_remove_element('vc_tabs');
    vc_remove_element('vc_tour');
    vc_remove_element('vc_hoverbox');
}

/**
 * Extend built-in modules
 */
if( function_exists('vc_add_param') ) {
    vc_add_param('vc_row', array(
        'type' => 'checkbox',
        'class' => '',
        'heading' => 'Restrict width?',
        'param_name' => 'restrict_width',
        'value' => 'true',
        'description' => _x('Restrict contents to content width.', 'visualcomposer'),
    ));
}


function checkVCAttribute($atts, $key, $return = '')
{
    return isset($atts[$key]) ? $atts[$key] : $return;
}

function setVCAttribute(&$atts, $key, $return = '')
{
    if (!isset($atts[$key])) {
        $atts[$key] = $return;
    }
}
