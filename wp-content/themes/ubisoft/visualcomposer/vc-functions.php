<?php

/**
 * Add file picker shortcode param.
 *
 * @param array $settings   Array of param seetings.
 * @param int   $value      Param value.
 */
function file_picker_settings_field( $settings, $value )
{
    $output = '';
    $select_file_class = '';
    $remove_file_class = ' hidden';
    $attachment_url = wp_get_attachment_url( $value );
    if ( $attachment_url ) {
        $select_file_class = ' hidden';
        $remove_file_class = '';
    }
    $output .= '<div class="file_picker_block">
                <div class="' . esc_attr( $settings['type'] ) . '_display">' .
        $attachment_url .
        '</div>
                <input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
        esc_attr( $settings['param_name'] ) . ' ' .
        esc_attr( $settings['type'] ) . '_field" value="' . esc_attr( $value ) . '" />
                <button class="button file-picker-button' . $select_file_class . '">Select File</button>
                <button class="button file-remover-button' . $remove_file_class . '">Remove File</button>
              </div>
              ';
    return $output;
}
vc_add_shortcode_param( 'file_picker', 'file_picker_settings_field', get_template_directory_uri() . '/visualcomposer/extend/file_picker.js' );

/**
 * Add post relation shortcode param.
 */
function my_link_settings_field( $settings, $value )
{
    return '<div class="my_link_block">
            <a href="#" class="button my_link_button">Select URL</a>
            <span style="font-weight: 700">Title:</span>
            <span class="my_link_title">-</span>
            <span style="font-weight: 700">URL:</span>
            <span class="my_link_url">-</span>
            <input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" value="' . esc_attr( $value ) . '" />
        </div>';
}
vc_add_shortcode_param( 'my_link', 'my_link_settings_field', get_template_directory_uri() . '/visualcomposer/extend/my_link.js' );

function insert_post_url_vars()
{
    $all_posts = get_posts([
        'numberposts' => -1,
        'post_type' => 'any',
        'suppress_filters' => false
    ]);
    $post_url_mapping = [];
    foreach ($all_posts as $one_post) {
        $post_url_mapping[$one_post->ID] = get_the_permalink($one_post->ID);
    }

    wp_localize_script(
        'jquery',
        'vc_url_mappping',
        $post_url_mapping
    );
}
add_action('vc_after_init', 'insert_post_url_vars');

function parse_my_link_vars($vars)
{
    if (empty($vars)) {
        $link = [
            'text' => '',
            'target' => '',
            'href' => '#'
        ];
    } else {
        $vars = str_replace('``', '"', $vars);
        $my_link = json_decode($vars);
        $link = [
            'text' => $my_link->text,
            'target' => $my_link->target
        ];

        $link['href'] = empty($my_link->id) ? $my_link->href : get_the_permalink($my_link->id);
    }

    return $link;
}

function make_section_classes($atts, $wrapper_class = "")
{
    if (!empty($atts['padding_top'])) {
        $wrapper_class .= " section-padding-top";
    }

    if (!empty($atts['padding_bottom'])) {
        $wrapper_class .= " section-padding-bottom";
    }

    return trim($wrapper_class);
}