<?php
/**
 * VC Module: Title and Text
 */

class ubisoft_title_text extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_title_text', array($this, 'element_frontend'));
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
                'name' => _x('Title with Text', 'visualcomposer'),
                'description' => _x('Creates a "Title and Text" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_title_text',
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
			<h3>Title and Text</h3>
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
        $title = empty($atts['title']) ? '' : $atts['title'];
        $text = empty($atts['text']) ? '' : $atts['text'];

        $wrapper_class = make_section_classes($atts, '');

        return '
            <section class="studios-games-heading">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="h2">' . $title . '</h2>
                        </div>
                        <div class="col-md-4">
                            <p>' . $text . '</p>
                        </div>
                    </div>
                </div>
          </section>';
    }
}

new ubisoft_title_text();
