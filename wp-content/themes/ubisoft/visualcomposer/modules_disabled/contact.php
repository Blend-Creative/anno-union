<?php
/**
 * VC Module: Headline
 */

class ubisoft_getInTouch extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array( $this, 'element_mapping' ));
        add_shortcode('ubisoft_getInTouch', array( $this, 'element_frontend' ));
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
                'name'        => _x('Get in Touch', 'visualcomposer'),
                'description' => _x('Creates a "Get in Touch" section".', 'visualcomposer'),
                'category'    => _x('Content', 'visualcomposer'),
                'base'        => 'ubisoft_getInTouch',
                'params'      => array(
                    array(
                        'type'          => 'attach_image',
                        'heading'       => _x('Image', 'visualcomposer'),
                        'param_name'    => 'image_id',
                    ),
                    array(
                        'type'          => 'textarea',
                        'heading'       => _x('Title', 'visualcomposer'),
                        'param_name'    => 'title',
                    ),
                    array(
                        'type'          => 'textarea',
                        'heading'       => _x('Contact Name', 'visualcomposer'),
                        'param_name'    => 'contact_name',
                    ),
                    array(
                        'type' => 'textarea_html',
                        'class' => '',
                        'heading' => __('Contact Details', 'visualcomposer'),
                        'param_name' => 'content',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Padding Top', 'visualcomposer'),
                        'param_name' => 'padding_top',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Padding Bottom', 'visualcomposer'),
                        'param_name' => 'padding_bottom',
                    )
                ),
                'admin_enqueue_js' => get_template_directory_uri() . '/assets/backend.js',
                'js_view'          => 'CustomElementView',
                'custom_markup'    => $this->element_backend(),
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
			<h3>Get in Touch Section</h3>
			{{ params.title }}<br />
			{{ params.contact_name }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $title = 'Get in touch';
        $contact_name = '';
        $image = '';
        $contact_details = '';

        if (isset($atts['image_id'])) {
            $image = wp_get_attachment_image_url($atts['image_id'], 'xxl');
        }

        if (isset($atts['title'])) {
            $title = $atts['title'];
        }

        if (isset($atts['contact_name'])) {
            $contact_name = $atts['contact_name'];
        }

        if (isset($content)) {
            $contact_details = $content;
        }

        $wrapper_class = make_section_classes($atts, 'about2-get-in-touch');

        $html = '
			<section class="' . $wrapper_class . '">
				<div class="container">
					<div class="row">
						<div class="col-md-6 text-center">
						<h2 class="h2">' . $title . '</h2>
						</div>
						<div class="col-md-2 text-center">
							<img class="about2-get-in-touch-avatar" src="' . $image . '" alt="">
						</div>
						<div class="col-md-4 about2-get-in-touch-details">
							<h3 class="h3">' . $contact_name . '</h3>
							' . $contact_details . '
						</div>
					</div>
				</div>
			</section>
    	';

        return $html;
    }
}

new ubisoft_getInTouch();
