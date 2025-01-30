<?php
/**
 * VC Module: Headline
 */

class ubisoft_game_slider_stage extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_game_slider_stage', array($this, 'element_frontend'));
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
                'name' => _x('Game Slider Stage', 'visualcomposer'),
                'description' => _x('Creates a "Game Slider Stage" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_game_slider_stage',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
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
                                'type' => 'my_link',
                                'heading' => _x('Studio Website Link', 'visualcomposer'),
                                'param_name' => 'studio_link',
                            ),
                            array(
                                'type' => 'my_link',
                                'heading' => _x('Game Website Link', 'visualcomposer'),
                                'param_name' => 'game_link',
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
			<h3>Game Slider Stage</h3>
			{{ params.title }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $title = empty($atts['title']) ? '' : $atts['title'];

        $slides = empty($atts['members']) ? [] : vc_param_group_parse_atts($atts['members']);
        $slider_html = $this->getSliderHTML($slides, $title);

        $wrapper_class = make_section_classes($atts, '');

        return '<div class="' . $wrapper_class . '">' . $slider_html . '</div>';
    }

    private function getSliderHTML($slides, $title)
    {
        $slides_html = [];
        $nav_html = [];

        if (!empty($slides)) {
            foreach ($slides as $index => $slide) {
                $image = empty($slide['image_id']) ? '' : wp_get_attachment_image_url($slide['image_id'], 'xxl');

                if (empty($slide['studio_link'])) {
                    $studioLinkHTML = '';
                } else {
                    $studioLink = parse_my_link_vars($slide['studio_link']);
                    $studioLinkHTML = '<a href="' . $studioLink['href'] . '" class="button js-slide-link" title="' . $studioLink['text'] . '" target="' . $studioLink['target'] . '"><span>' . $studioLink['text'] . '</span></a>';
                }

                if (empty($slide['game_link'])) {
                    $gameLinkHTML = '';
                } else {
                    $gameLink = parse_my_link_vars($slide['game_link']);
                    $gameLinkHTML = '<a href="' . $gameLink['href'] . '" class="button js-slide-link" title="' . $gameLink['text'] . '" target="' . $gameLink['target'] . '"><span>' . $gameLink['text'] . '</span></a>';
                }

                if (empty($studioLinkHTML) && empty($gameLinkHTML)) {
                    $studioLinkHTML = '<span class="js-slide-link-placeholder">&nbsp;</span>';
                }

                $slides_html[] = '
                    <div class="slideshow-slide">
                        <div class="slideshow-img">
                            <img class="tns-lazy-img" data-src="' . $image . '" />
                        </div>
                        <div class="container">
                            <h2 class="h1 hero-heading">' . $title . '</h2>
                            <div class="slideshow-heading">
                                <h2 class="h3 js-slide-title">' . $slide['title'] . '</h2>
                                ' . $studioLinkHTML . ' ' . $gameLinkHTML . '
                            </div>
                        </div>
                    </div>';

                $nav_html[] = '
                    <div class="col-2" data-slide-idx="' . $index . '">
                        <div class="slideshow-pager-item"><span>' . $slide['title'] . '</span></div>
                    </div>';
            }
        }

        return '
            <div class="js-game-slideshow js-game-slideshow-stage">
                <section class="studios-games-heading">
                    <div class="container">
                        <h2 class="h1 hero-heading">' . $title . '</h2>
                    </div>
                </section>
                <div class="slideshow-gal">
                    ' . implode('', $slides_html) . '
                </div>
                <div class="slideshow-pager">
                    <div class="container">
                        <div class="row" style="justify-content: flex-end;">
                            ' . implode('', $nav_html) . '
                        </div>
                    </div>
                </div>
                <div class="js-game-slideshow-stage-scroll-down hero-scroll-down-container container">
                    <div class="hero-scroll-down"><span>SCROLL DOWN</span></div>
                </div>
            </div>';
    }
}

new ubisoft_game_slider_stage();
