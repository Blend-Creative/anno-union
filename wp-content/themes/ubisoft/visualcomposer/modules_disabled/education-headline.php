<?php
/**
 * VC Module: Headline
 */

class ubisoft_education_headline extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_education_headline', array($this, 'element_frontend'));
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
                'name' => _x('Education Headline', 'visualcomposer'),
                'description' => _x('Creates a "Education Headline" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_education_headline',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Text', 'visualcomposer'),
                        'param_name' => 'text',
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
			<h3>Education Headline</h3>
			{{ params.title }}<br />
			<br />
			{{ params.text }}
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
        if (!isset($atts['text'])) {
            $atts['text'] = '';
        }

        $wrapper_class = make_section_classes($atts, 'education-intro');

        $html = '
      <section class="' . $wrapper_class . '">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-4">
              <h2 class="h2">' . $atts['title'] . '</h2>
            </div>
            <div class="col-md-4">
              <p>
                ' . $atts['text'] . '
              </p>
            </div>
          </div>
        </div>
      </section>
    ';

        return $html;
    }

}

new ubisoft_education_headline();
