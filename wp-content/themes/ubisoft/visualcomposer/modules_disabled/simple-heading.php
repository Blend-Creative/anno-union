<?php
/**
 * VC Module: Simple Heading
 */

class ubisoft_simple_heading extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_simple_heading', array($this, 'element_frontend'));
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
                'name' => _x('Simple Heading', 'visualcomposer'),
                'description' => _x('Creates a simple page heading.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_simple_heading',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
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
     * {{ params }} are rendered in /assets/backend.js
     */
    public function element_backend()
    {
        $html = '<div class="module module-headline">
			<h3>Simple Heading</h3>
			{{ params.title }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        // the first option is not stored by visual composer, so we have to add it manually
        if (!isset($atts['title'])) {
            $atts['title'] = '';
        }

        $html = '
        <div class="container ext-container-simple-heading">
            <h2 class="h2">
            ' . $atts['title'] . '
            </h2>
        </div>
        ';

        return $html;
    }
}

new ubisoft_simple_heading();
