<?php
/**
 * VC Module: Headline
 */

class ubisoft_education_columns extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_education_columns', array($this, 'element_frontend'));
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
                'name' => _x('Education Columns', 'visualcomposer'),
                'description' => _x('Creates a "Education Columns" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_education_columns',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Subtitle', 'visualcomposer'),
                        'param_name' => 'subtitle',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Column 1 Text', 'visualcomposer'),
                        'param_name' => 'column_1',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Column 2 Text', 'visualcomposer'),
                        'param_name' => 'column_2',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Column 3 Text', 'visualcomposer'),
                        'param_name' => 'column_3',
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
			<h3>Education Columns</h3>
			{{ params.title }}<br />
			{{ params.subtitle }}<br />
			<br />
			{{ params.column_1 }}<br />
			{{ params.column_2 }}<br />
			{{ params.column_3 }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {

        if (!isset($atts['title'])) {
            $atts['title'] = '';
        }
        if (!isset($atts['subtitle'])) {
            $atts['subtitle'] = '';
        }
        if (!isset($atts['column_1'])) {
            $atts['column_1'] = '';
        }
        if (!isset($atts['column_2'])) {
            $atts['column_2'] = '';
        }
        if (!isset($atts['column_3'])) {
            $atts['column_3'] = '';
        }

        $wrapper_class = make_section_classes($atts, 'education-article');

        $html = '
		<section class="' . $wrapper_class . '">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<h2 class="h2">' . $atts['title'] . '</h2>
					</div>
					<div class="col-md-4 offset-md-2">
						<h3 class="h3">' . $atts['subtitle'] . '</h3>
					</div>
				</div>

				<div class="row education-columns">
					<div class="col-md-4">
						<p>
						' . $atts['column_1'] . '
						</p>
					</div>
					<div class="col-md-4">
						<p>
						' . $atts['column_2'] . '
						</p>
					</div>
					<div class="col-md-4">
						<p>
						' . $atts['column_3'] . '
						</p>
					</div>
				</div>
			</div>
		</section>
    ';

        return $html;
    }

}

new ubisoft_education_columns();
