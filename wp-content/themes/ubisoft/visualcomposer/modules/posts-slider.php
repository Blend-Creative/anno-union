<?php
/**
 * VC Module: Posts Slider
 */

class ubisoft_posts_slider extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_posts_slider', array($this, 'element_frontend'));
    }

    /**
     * Element mapping.
     */
    public function element_mapping()
    {

        // Stop all if VC is not enabled
        if (!defined('WPB_VC_VERSION')) {
            return;
        }

        // Map the block with vc_map()
        vc_map(
            array(
                'name' => _x('Posts Slider', 'visualcomposer'),
                'description' => _x('Customizable posts slider.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_posts_slider',
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Variant', 'visualcomposer'),
                        'param_name' => 'variant',
                        'value' => array(
                            'Default' => '',
                            'Hero' => 'hero',
                        ),
                        'std' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'vc_link',
                        'heading' => _x('Primary Button', 'visualcomposer'),
                        'param_name' => 'primary_button',
                    ),
                    array(
                        'type' => 'loop',
                        'class' => '',
                        'heading' => _x('Loop', 'visualcomposer'),
                        'param_name' => 'loop',
                        'value' => '',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Title Color', 'visualcomposer'),
                        'param_name' => 'title_color',
                        'value' => array(
                            'Black' => 'black',
                            'Purple' => 'purple',
                            'Scroll' => 'scroll',
                        ),
                        'std' => 'scroll'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Background Color', 'visualcomposer'),
                        'param_name' => 'background_color',
                        'value' => array(
                            'None' => '',
                            'Stone' => 'stone',
                            'Scroll' => 'scroll',
                        ),
                        'std' => ''
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Background Image', 'visualcomposer'),
                        'param_name' => 'background_image',
                        'value' => array(
                            'None' => '',
                            'Anno Logo' => get_template_directory_uri() . '/assets/images/anno117-logo-bg.webp',
                        ),
                        'std' => ''
                    ),
                ),
                'admin_enqueue_js' => get_template_directory_uri() . '/assets/backend.js',
                'js_view' => 'CustomElementView',
                'custom_markup' => $this->element_backend(),
            )
        );
    }

    /**
     * Element backend.
     * {{ params }} are rendered in get_template_directory_uri() . '/assets/backend.js
     */
    public function element_backend()
    {
        $html = '<div class="module module-headline">
			<h3>Posts Slider</h3>
			{{ params.title }}<br />
			Variant: {{ params.variant }}<br />
			Loop: {{ params.loop }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        // add button
        $button = false;
        if (!empty($atts['primary_button'])) {
            $button = vc_build_link($atts['primary_button']);
        }

        // add items
        $items = false;
        if (!empty($atts['loop'])) {
            list($args, $wp_query) = vc_build_loop_query($atts['loop']);
            $items = $wp_query->posts;
        }

        ob_start();
        get_template_part( 'template-parts/posts-slider', null, array(
            'variant' => $atts['variant'] ?? 'default',
            'title_color' => !empty($atts['title_color']) ? 'color-' . $atts['title_color'] : 'color-purple',
            'background_color' => !empty($atts['background_color']) ? 'bg-' . $atts['background_color'] : false,
            'background_image' => $atts['background_image'] ?? false,
            'title' => $atts['title'] ?? false,
            'button' => $button,
            'items' => $items,
        ));
        $template = ob_get_contents();
        ob_end_clean();
        return $template;
    }
}

new ubisoft_posts_slider();
