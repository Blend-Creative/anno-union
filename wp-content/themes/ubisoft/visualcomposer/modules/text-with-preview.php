<?php
/**
 * VC Module: Text with Preview
 */

class ubisoft_text_with_preview extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_text_with_preview', array($this, 'element_frontend'));
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
                'name' => _x('Text with Preview', 'visualcomposer'),
                'description' => _x('Creates a "Text with Preview" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_text_with_preview',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'heading',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Heading Type (for SEO)', 'visualcomposer'),
                        'param_name' => 'heading_type',
                        'value' => array(
                            'h1' => 'h1',
                            'h2' => 'h2',
                            'h3' => 'h3',
                            'h4' => 'h4',
                            'h5' => 'h5',
                            'h6' => 'h6'
                        ),
                        'std' => 'none'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Heading Visual Type', 'visualcomposer'),
                        'param_name' => 'heading_type_visual',
                        'value' => array(
                            'h1' => 'h1',
                            'h2' => 'h2',
                            'h3' => 'h3',
                            'h4' => 'h4',
                            'h5' => 'h5',
                            'h6' => 'h6'
                        ),
                        'std' => 'none'
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Text (before fold)', 'visualcomposer'),
                        'param_name' => 'text_before',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Text (after fold)', 'visualcomposer'),
                        'param_name' => 'text_after',
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
			<h3>Text with Preview</h3>
			{{ params.heading }}<br />
			{{ params.text_before }}<br />
			{{ params.text_after }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $heading = empty($atts['heading']) ? '' : $atts['heading'];
        $text_before = empty($atts['text_before']) ? '' : $atts['text_before'];
        $text_after = empty($atts['text_after']) ? '' : $atts['text_after'];

        // Remove any paragraphs
        $text_before = str_replace('<p>', '', str_replace('</p>', '<br><br>', $text_before));
        $text_after = str_replace('<p>', '', str_replace('</p>', '<br><br>', $text_after));

        $heading_html = '<h2 class="h2">' . $heading . '</h2>';

        if (empty($atts['heading_type_visual'])) {
            $atts['heading_type_visual'] = 'h1';
        }

        if (!empty($atts['heading_type'])) {
            if ($atts['heading_type'] == 'h1') {
                $heading_html = '<h1 class="' . $atts['heading_type_visual'] . '" style="margin-bottom: 20px">' . $heading . '</h1>';
            } else if ($atts['heading_type'] == 'h2') {
                $heading_html = '<h2 class="' . $atts['heading_type_visual'] . '" style="margin-bottom: 20px">' . $heading . '</h2>';
            } else if ($atts['heading_type'] == 'h3') {
                $heading_html = '<h3 class="' . $atts['heading_type_visual'] . '" style="margin-bottom: 20px">' . $heading . '</h3>';
            } else if ($atts['heading_type'] == 'h4') {
                $heading_html = '<h4 class="' . $atts['heading_type_visual'] . '" style="margin-bottom: 20px">' . $heading . '</h4>';
            } else if ($atts['heading_type'] == 'h5') {
                $heading_html = '<h5 class="' . $atts['heading_type_visual'] . '" style="margin-bottom: 20px">' . $heading . '</h5>';
            } else if ($atts['heading_type'] == 'h6') {
                $heading_html = '<h6 class="' . $atts['heading_type_visual'] . '" style="margin-bottom: 20px">' . $heading . '</h6>';
            }
        }

        $wrapper_class = make_section_classes($atts, '');

        $readMore = ICL_LANGUAGE_CODE == 'de' ? 'Mehr erfahren' : 'Read more';

        return '
            <section class="text-with-preview-container">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10 offset-md-0" data-text-preview data-button-text="' . $readMore . '">
                            ' . $heading_html . '
                            <p data-state="before">' . $text_before . '</p>
                            <p data-state="after">' . $text_after . '</p>
                        </div>
                    </div>
                </div>
          </section>';
    }
}

new ubisoft_text_with_preview();
