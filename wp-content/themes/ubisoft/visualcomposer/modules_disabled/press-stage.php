
<?php
/**
 * VC Module: Headline
 */

class ubisoft_press_stage extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_press_stage', array($this, 'element_frontend'));
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
                'name' => _x('Press Stage', 'visualcomposer'),
                'description' => _x('Creates a "Press Stage" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_press_stage',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Text', 'visualcomposer'),
                        'param_name' => 'copy',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Person Image', 'visualcomposer'),
                        'param_name' => 'image_id',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Person Title', 'visualcomposer'),
                        'param_name' => 'person_title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Person Name', 'visualcomposer'),
                        'param_name' => 'person_name',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Person Position', 'visualcomposer'),
                        'param_name' => 'person_position',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Person Email Address', 'visualcomposer'),
                        'param_name' => 'person_email',
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
			<h3>Press Stage</h3>
			{{ params.title }}<br />
			{{ params.copy }}
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
        if (!isset($atts['copy'])) {
            $atts['copy'] = '';
        }
        if (!isset($atts['image_id'])) {
            $atts['image'] = '';
        } else {
            $atts['image'] = wp_get_attachment_image_url($atts['image_id'], 'xxl');
        }
        if (!isset($atts['person_name'])) {
            $atts['person_name'] = '';
        }
        if (!isset($atts['person_position'])) {
            $atts['person_position'] = '';
        }
        if (!isset($atts['person_email'])) {
            $atts['person_email'] = '';
        }
        if (!isset($atts['person_title'])) {
            $atts['person_title'] = '';
        }

        $scrollDownText = ICL_LANGUAGE_CODE == 'en' ? 'SCROLL DOWN' : 'HERUNTERSCROLLEN';

        $html = '
        <section class="press-hero">
            <div class="container">
                <div>
                    <div class="row">
                        <div class="col-md-5">
                            <h2 class="h1">' . $atts['title'] . '</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <h3 class="h4">
                                ' . $atts['copy'] . '
                            </h3>
                        </div>
                        <div class="col-2 col-md-1 offset-md-3">
                            <img class="press-avatar" src="' . $atts['image'] . '" alt="" />
                        </div>
                        <div class="col-10 col-md-3">
                            <p>' . $atts['person_title'] . '</p>
                            <h4 class="h3">' . $atts['person_name'] . '</h4>
                            <p>
                            ' . $atts['person_position'] . '<br />
                            <a href="mailto:' . $atts['person_email'] . '">' . $atts['person_email'] . '</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container" style="position: absolute; bottom: 45px; left: 20px; right: 20px;">
                <div class="hero-scroll-down-container">
                    <div class="hero-scroll-down">
                        <span>' . $scrollDownText . '</span>
                    </div>
                </div>
            </div>
        </section>
        ';

        return $html;
    }
}

new ubisoft_press_stage();