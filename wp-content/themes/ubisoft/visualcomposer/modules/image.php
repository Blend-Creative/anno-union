<?php
/**
 * VC Module: Image
 */

class ubisoft_image extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_image', array($this, 'element_frontend'));
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
                'name' => _x('Image', 'visualcomposer'),
                'description' => _x('Includes a single image.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_image',
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Image', 'visualcomposer'),
                        'param_name' => 'image_id',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Alignment', 'visualcomposer'),
                        'param_name' => 'alignment',
                        'value' => array(
                            'None' => 'none',
                            'Left' => 'left',
                            'Centre' => 'center',
                            'Right' => 'right'
                        ),
                        'std' => 'none'
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
			<h3>Image</h3>
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $image = empty($atts['image_id']) ? '' : wp_get_attachment_image($atts['image_id'], 'xxl');

        if (!isset($atts['alignment'])) { $atts['alignment'] = 'left'; }

        return '<div class="simple-image ' . $atts['alignment'] . '">' . $image . '</div>';
    }
}

new ubisoft_image();
