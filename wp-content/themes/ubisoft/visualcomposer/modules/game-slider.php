<?php
/**
 * VC Module: Headline
 */

class ubisoft_game_slider extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_game_slider', array($this, 'element_frontend'));
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
                'name' => _x('Game Slider', 'visualcomposer'),
                'description' => _x('Creates a "Game Slider" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_game_slider',
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
                        'type' => 'param_group',
                        'value' => '',
                        'param_name' => 'members',
                        // Note params is mapped inside param-group:
                        'params' => array(
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Title', 'visualcomposer'),
                                'param_name' => 'title',
                                'admin_label' => true
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Copy', 'visualcomposer'),
                                'param_name' => 'copy',
                            ),
                            array(
                                'type' => 'my_link',
                                'heading' => _x('Link', 'visualcomposer'),
                                'param_name' => 'my_link',
                            ),
                            array(
                                'type' => 'attach_image',
                                'heading' => _x('Image', 'visualcomposer'),
                                'param_name' => 'image_id',
                            ),
                            /*
                            array(
                                'type' => 'dropdown',
                                'heading' => _x('Title Color', 'visualcomposer'),
                                'param_name' => 'title_color',
                                'value' => array(
                                    'Scroll' => 'scroll',
                                    'Stone' => 'stone',
                                    'Plum' => 'plum',
                                    'Purple' => 'purple',
                                ),
                                'std' => 'scroll'
                            ),
                            */
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
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('No Bottom Spacing', 'visualcomposer'),
                        'param_name' => 'no_spacing_bottom',
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
			<h3>Game Slider</h3>
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
        $header_html = $this->getHeaderHTML($title, $text);

        $slides = empty($atts['members']) ? [] : vc_param_group_parse_atts($atts['members']);
        $slider_html = $this->getSliderHTML($slides);

        $wrapper_class = make_section_classes($atts, '');

        $no_bottom_spacing_class = !empty($atts['no_spacing_bottom']) ? 'no_spacing_bottom' : null;

        $html = '<div class="' . $wrapper_class . ' ' . $no_bottom_spacing_class . '">';
        if (!empty($atts['title']) || !empty($atts['text'])) {
            $html .= $header_html;
        }
        $html .= $slider_html;
        $html .= '</div>';

        return $html;
    }

    private function getHeaderHTML($title, $text)
    {
        return '
            <section class="studios-games-heading">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <h2 class="h3">' . $title . '</h2>
                        </div>
                        <div class="col-md-4 offset-md-1">
                            <p>' . $text . '</p>
                        </div>
                    </div>
                </div>
          </section>';
    }

    private function getSliderHTML($slides)
    {
        $slides_html = [];
        $nav_html = [];

        if (!empty($slides)) {
   
            foreach ($slides as $index => $slide) {
                $image = empty($slide['image_id']) ? '' : wp_get_attachment_image_url($slide['image_id'], 'xxl');
                $link = parse_my_link_vars(empty($slide['my_link']) ? null : $slide['my_link']);
            
                $slides_html[] = '
                    <div class="slideshow-slide">
                        <div class="slideshow-img">
                            <img class="tns-lazy-img" data-src="' . esc_url($image) . '" />
                        </div>
                        <div class="container relative">
                            <div class="slideshow-heading">
                                <h2 class="h2 js-slide-title">' . ($slide['title'] ?? '') . '</h2>
                                ' . (!empty($slide['copy']) ? '<p class="js-slide-copy">' . esc_html($slide['copy']) . '</p>' : '') . '
                                ' . (!empty($link) && !empty($link['href']) ? '
                                    <a href="' . esc_url($link['href']) . '" class="button js-slide-link" title="' . esc_attr($link['text']) . '" target="' . esc_attr($link['target']) . '"><span>' . esc_html($link['text']) . '</span></a>
                                ' : '') . '
                            </div>
                        </div>
                    </div>';
            
                $nav_html[] = '
                    <div class="col-2" data-slide-idx="' . esc_attr($index) . '">
                        <div class="slideshow-pager-item"><span>' . $slide['title'] . '</span></div>
                    </div>';
            }

        }

        return '
            <div class="js-game-slideshow">
                <div class="slideshow-gal">
                    ' . implode('', $slides_html) . '
                </div>
                <div class="slideshow-pager">
                    <div class="container">
                        <div class="row">
                            ' . implode('', $nav_html) . '
                        </div>
                    </div>
                </div>
            </div>';
    }
}

new ubisoft_game_slider();
