<?php
/**
 * VC Module: Headline
 */

class ubisoft_milestones extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_milestones', array($this, 'element_frontend'));
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
                'name' => _x('Milestones', 'visualcomposer'),
                'description' => _x('Creates a "Milestones" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_milestones',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'param_group',
                        'value' => '',
                        'param_name' => 'milestones',
                        // Note params is mapped inside param-group:
                        'params' => array(
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Year', 'visualcomposer'),
                                'param_name' => 'year',
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Title', 'visualcomposer'),
                                'param_name' => 'title',
                                'admin_label' => true
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Text', 'visualcomposer'),
                                'param_name' => 'text'
                            ),
                            array(
                                'type' => 'attach_image',
                                'heading' => _x('Image', 'visualcomposer'),
                                'param_name' => 'image_id',
                            )
                        )
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
			<h3>Milestones</h3>
			{{ params.title }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        if (empty($atts['milestones'])) {
            return '';
        }

        $title = empty($atts['title']) ? '' : $atts['title'];
        $header_html = $this->getHeaderHTML($title);

        $slides = vc_param_group_parse_atts($atts['milestones']);
        $slider_html = $this->getSliderHTML($slides);

        $wrapper_class = make_section_classes($atts, 'about-milestones about-us-milestones');

        return '<div class="' . $wrapper_class . '">' . $header_html . ' ' . $slider_html . '</div>';
    }

    private function getHeaderHTML($title)
    {
        return '
            <div class="container">
              <div class="row">
                <div class="col-md-12">
                  <h2 class="h2">' . $title . '</h2>
                </div>
              </div>
            </div>';
    }

    private function getSliderHTML($slides)
    {
        $slides_html = [];

        foreach ($slides as $index => $slide) {
            foreach (['title', 'text', 'year'] as $key) {
                setVCAttribute($slide, $key);

                if ('text' === $key) {
                    $slide[$key] = nl2br($slide[$key]);
                }
            }

            $image = empty($slide['image_id']) ? '' : wp_get_attachment_image_url($slide['image_id'], 'xxl');

            $slides_html[] = '
                <div class="slideshow-slide slideshow-slide-' . $index . '" data-year="' . $slide['year'] . '">
                    <div class="slideshow-img">
                        <img class="tns-lazy-img" data-src="' . $image . '" />
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-5 offset-md-6">
                                <div class="about-milestones-slide-content">
                                    <h4 class="h4 js-milestone-year">' . $slide['year'] . '</h4>
                                    <h3 class="h3 js-milestone-title">' . $slide['title'] . '</h3>
                                    <p class="js-milestone-content">' . $slide['text'] . '</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
        }

        return '
        <div class="js-milestones-slideshow">
            <div class="slideshow-gal">
                ' . implode('', $slides_html) . '
            </div>
            <div class="slideshow-pager">
                <div class="container">
                    <ul class="about-milestones-timeline js-milestones-slides"></ul>
                </div>
            </div>
        </div>
            ';
    }
}

new ubisoft_milestones();
