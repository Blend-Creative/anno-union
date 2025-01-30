<?php
/**
 * VC Module: Headline
 */

class ubisoft_stage extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_stage', array($this, 'element_frontend'));
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
                'name' => _x('Stage', 'visualcomposer'),
                'description' => _x('Creates a stage.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_stage',
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Stage Type', 'visualcomposer'),
                        'param_name' => 'stage_type',
                        'value' => array(
                            'Headline only' => 'headline_only',
                            'Headline with Links' => 'headline_with_links',
                            'Headline with Text' => 'headline_with_text',
                            'Job Search' => 'job_search'
                        ),
                        'std' => 'headline_only'
                    ),
                    // array(
                    // 	'type'          => 'dropdown',
                    // 	'heading'       => _x('Background Type', 'visualcomposer'),
                    // 	'param_name'    => 'background_type',
                    // 	'value' => array(
                    // 		'Full' => 'background_full',
                    // 		'50% Image / 50% Solid Colour' => 'background_half'
                    // 	),
                    // ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Background Image', 'visualcomposer'),
                        'param_name' => 'image_id',
                    ),
                    array(
                        'type' => 'file_picker',
                        'heading' => 'Video',
                        'param_name' => 'video_id',
                        'value' => '',
                        'description' => 'Enter Background Video (MP4)',
                        'dependency' => [
                            [
                                'element' => 'stage_type',
                                'value' => [
                                    'headline_only',
                                    'headline_with_links',
                                    'job_search'
                                ]
                            ]
                        ]
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Sub-Title', 'visualcomposer'),
                        'param_name' => 'sub_title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Text', 'visualcomposer'),
                        'param_name' => 'copy',
                    ),
                    array(
                        'type' => 'my_link',
                        'heading' => _x('Link #1', 'visualcomposer'),
                        'param_name' => 'my_link_1',
                    ),
                    array(
                        'type' => 'my_link',
                        'heading' => _x('Link #2', 'visualcomposer'),
                        'param_name' => 'my_link_2',
                    ),
                    array(
                        'type' => 'my_link',
                        'heading' => _x('Link #3', 'visualcomposer'),
                        'param_name' => 'my_link_3',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Search Input Placeholder', 'visualcomposer'),
                        'param_name' => 'placeholder',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Search Button', 'visualcomposer'),
                        'param_name' => 'search_button',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Filter Text', 'visualcomposer'),
                        'param_name' => 'filter_text',
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
     * {{ params }} are rendered in /assets/backend.js
     */
    public function element_backend()
    {
        $html = '<div class="module module-headline">
			<h3>Stage</h3>
			{{ params.title }}<br />
			{{ params.sub_title }}<br />
			<br />
			{{ params.copy }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        // the first option is not stored by visual composer, so we have to add it manually
        if (!isset($atts['title'])) {
            $atts['title'] = '';
        }
        if (!isset($atts['sub_title'])) {
            $atts['sub_title'] = '';
        }
        if (!isset($atts['copy'])) {
            $atts['copy'] = '';
        }
        if (!isset($atts['placeholder'])) {
            $atts['placeholder'] = 'Enter keyword or job title';
        }
        if (!isset($atts['search_button'])) {
            $atts['search_button'] = 'Search';
        }
        if (!isset($atts['filter_text'])) {
            $atts['filter_text'] = 'Filter by location:';
        }
        if (!isset($atts['stage_type'])) {
            $atts['stage_type'] = 'headline_only';
        }

        $video = empty($atts['video_id']) ? '' : wp_get_attachment_url($atts['video_id']);

        if (isset($atts['image_id']) && $atts['image_id'] !== '') {
            $image = wp_get_attachment_image_url($atts['image_id'], 'xxl');
        } else {
            $image = get_template_directory_uri() . '/assets/images/bluebyte-jobs-hero-background.jpg';
        }

        $links = '';

        $link_1 = parse_my_link_vars(empty($atts['my_link_1']) ? null : $atts['my_link_1']);
        $link_2 = parse_my_link_vars(empty($atts['my_link_2']) ? null : $atts['my_link_2']);
        $link_3 = parse_my_link_vars(empty($atts['my_link_3']) ? null : $atts['my_link_3']);

        if ($atts['stage_type'] == 'headline_with_links') {
            if ('#' !== $link_1['href']) {
                $links .= '<a href="' . $link_1['href'] . '" class="button" title="' . $link_1['text'] . '" target="' . $link_1['target'] . '"><span>' . $link_1['text'] . '</span></a>';
            }

            if ('#' !== $link_2['href']) {
                $links .= '<a href="' . $link_2['href'] . '" class="button" title="' . $link_2['text'] . '" target="' . $link_2['target'] . '"><span>' . $link_2['text'] . '</span></a>';
            }

            if ('#' !== $link_3['href']) {
                $links .= '<a href="' . $link_3['href'] . '" class="button" title="' . $link_3['text'] . '" target="' . $link_3['target'] . '"><span>' . $link_3['text'] . '</span></a>';
            }
        }

        $scrollDownText = ICL_LANGUAGE_CODE == 'en' ? 'SCROLL DOWN' : 'HERUNTERSCROLLEN';

        if (isset($atts['stage_type'])) {
            if ($atts['stage_type'] == 'job_search') {
                $html = '
				<section class="hero jobs-hero" style="background-image: url(' . $image . ');">
					<div class="container">
						<div class="hero-heading-container">
							<h2 class="h1 hero-heading">' . $atts['title'] . '</h2>

							<h3 class="h4">' . $atts['sub_title'] . '</h3>

							<div class="row search-box-row">
								<div class="search-box-outer">
									<div class="search-box">
										<div class="cta">' . $atts['copy'] . '</div>

										<form action="" class="search-box-form">
											<div class="search-box-input">
												<input type="text" placeholder="' . $atts['placeholder'] . '" autofocus="autofocus" />
											</div>
											<button type="submit"><span>' . $atts['search_button'] . '</span></button>
										</form>

										<div class="search-box-filters">
											<div>' . $atts['filter_text'] . '</div>

											<button data-location-filter="Berlin">Berlin</button>
											<button data-location-filter="Düsseldorf">Düsseldorf</button>
											<button data-location-filter="Mainz">Mainz</button>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="hero-footer d-none d-md-block">
							<div class="hero-scroll-down-container">
								<div class="hero-scroll-down"><span>' . $scrollDownText . '</span></div>
							</div>
						</div>
					</div>
				</section>
				';
            } elseif ($atts['stage_type'] == 'headline_with_text') {

                // $atts['background_type']

                $html = '
				<section class="hero about-hero about-us-hero">
					<div class="about-hero-slideshow">
						<div class="slideshow-current-image js-current-image" style="background-image: url(' . $image . '); opacity: 1;"></div>
						<div class="slideshow-current-image js-next-image" style="opacity: 0; background-image: url(' . $image . ');"></div>
					</div>
					<div class="container">
						<div class="row about-hero-content-row">
							<div class="col-md-5">
					';

                $html .= '<h2 class="h2">
				' . $atts['title'] . '
				</h2>';

                if (isset($atts['sub_title'])) {
                    $html .= '<h3 class="h4">
					' . $atts['sub_title'] . '
					</h3>';
                }
                if (isset($atts['copy'])) {
                    $html .= '<p>
					' . $atts['copy'] . '
					</p>';
                }

                $html .= '
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<div class="hero-scroll-down-container">
									<div class="hero-scroll-down"><span>' . $scrollDownText . '</span></div>
								</div>
							</div>
						</div>
					</div>
				</section>
				';
            } else {
                $html = $this->getDefaultHTML($atts['title'], $video, $image, $links, $scrollDownText, true);
            }
        } else { // default
            $html = $this->getDefaultHTML($atts['title'], $video, $image, $links, $scrollDownText);
        }

        return $html;
    }

    private function getDefaultHTML($title, $video, $image, $links, $scrollDownText, $container = false)
    {
        $container_class = $container ? ' class="container"' : '';
        $background_video = empty($video) ?
            '' :
            '<div class="header-video-background" data-video="' . $video . '" data-poster="' . $image . '"><div></div></div>';

        return '
            <section class="hero homepage-hero" style="background-image: url(' . $image . ');">
                ' . $background_video . '
                <div' . $container_class . '>
                    <h2 class="h1 hero-heading">' . $title . '</h2>
                    <div class="hero-footer">
                        <div class="hero-cta-row">
                            ' . $links . '
                        </div>
        
                        <div class="hero-scroll-down-container">
                            <div class="hero-scroll-down">
                                <span>' . $scrollDownText . '</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>';
    }
}

new ubisoft_stage();
