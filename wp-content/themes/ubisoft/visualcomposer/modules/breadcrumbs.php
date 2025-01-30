<?php
/**
 * VC Module: Breadcrumbs
 */

class ubisoft_breadcrumbs extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_breadcrumbs', array($this, 'element_frontend'));
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
                'name' => _x('Breadcrumbs', 'visualcomposer'),
                'description' => _x('Shows the page\'s breadcrumbs.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_breadcrumbs',
                'params' => array(),
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
			<h3>Breadcrumbs</h3>
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
		$html = '<div class="container">';
		$html .= '<div class="row">';
		$html .= '<div class="col-sm-12 breadcrumbs-container">';
		if ( function_exists('yoast_breadcrumb') ) {
			$html .= yoast_breadcrumb( '<p id="breadcrumbs">','</p>', false );
		}
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';

        return $html;
    }
}

new ubisoft_breadcrumbs();
