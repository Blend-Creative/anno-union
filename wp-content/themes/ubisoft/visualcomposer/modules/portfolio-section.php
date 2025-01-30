<?php
/**
 * VC Module: Headline
 */

class ubisoft_portfolio_section extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_portfolio_section', array($this, 'element_frontend'));
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
                'name' => _x('Portfolio Section', 'visualcomposer'),
                'description' => _x('Creates a "Portfolio Section" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_portfolio_section',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Heading', 'visualcomposer'),
                        'param_name' => 'heading',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Sub-heading', 'visualcomposer'),
                        'param_name' => 'sub_heading',
                    ),
                    array(
                        'type' => 'attach_images',
                        'heading' => _x('Slideshow Images', 'visualcomposer'),
                        'param_name' => 'image',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Position', 'visualcomposer'),
                        'param_name' => 'position',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'textarea_html',
                        'heading' => _x('Text', 'visualcomposer'),
                        'param_name' => 'text',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Person Image', 'visualcomposer'),
                        'param_name' => 'person_image',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Person Name', 'visualcomposer'),
                        'param_name' => 'name',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Section Type', 'visualcomposer'),
                        'param_name' => 'section_type',
                        'value' => array(
                            'Image Slideshow' => 'image_slideshow',
                            'Video' => 'video',
                            'Audio' => 'audio'
                        ),
                        'std' => ''
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Section Layout', 'visualcomposer'),
                        'param_name' => 'section_layout',
                        'value' => array(
                            'Text Left' => 'text_left',
                            'Text Right' => 'text_right'
                        ),
                        'std' => ''
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Quote Title', 'visualcomposer'),
                        'param_name' => 'quote_title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Quote Text', 'visualcomposer'),
                        'param_name' => 'quote_text',
                    ),
                    array(
                        'type' => 'my_link',
                        'heading' => _x('"Apply Now" Link', 'visualcomposer'),
                        'param_name' => 'my_job_link',
                    ),
                    array(
                        'type' => 'my_link',
                        'heading' => _x('"Job Site" Link', 'visualcomposer'),
                        'param_name' => 'my_job_site_link',
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
			<h3>Portfolio Section</h3>
			{{ params.heading }}<br />
			{{ params.sub_heading }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $personImageSrc = !empty($atts['person_image']) ? wp_get_attachment_image_url($atts['person_image'], 'xxl') : null;
		$personImage = $personImageSrc ? '<img src="' . $personImageSrc . '" alt="" />' : null;

        if (!empty($atts['image']) && is_string($atts['image'])) {
            $images = explode(',', $atts['image']);

            $slideshowImages = '';
            $count = 0;
            $firstImage = '';
            foreach ($images as $image) {
                if ($count == 0) {
                    $firstImage = wp_get_attachment_image_url($image, 'xxl');
                    $slideshowImages .= wp_get_attachment_image_url($image, 'xxl');
                } else {
                    $slideshowImages .= ',' . wp_get_attachment_image_url($image, 'xxl');
                }
                $count++;
            }
        }

        $title = checkVCAttribute($atts, 'title');
        $text = $atts['text'] ?? '';
        $position = $atts['position'] ?? '';
        $quote_title = $atts['quote_title'] ?? '';
        $quote_text = $atts['quote_text'] ?? '';
        $name = $atts['name'] ?? '';

        $job_link = parse_my_link_vars(empty($atts['my_job_link']) ? null : $atts['my_job_link']);
        $job_site_link = parse_my_link_vars(empty($atts['my_job_site_link']) ? null : $atts['my_job_site_link']);

        if (isset($atts['heading'])) {
            $heading = '<h2 class="h4">' . $atts['heading'] . '</h2>';
        } else {
            $heading = '';
        }
        if (isset($atts['sub_heading'])) {
            $subHeading = '<h3 class="h2">' . $atts['sub_heading'] . '</h3>';
        } else {
            $subHeading = '';
        }

        if ($heading) {
            $titleSection = '<div class="row game-section-header">
        <div class="col-md-12">
          ' . $heading . '
          ' . $subHeading . '
        </div>
      </div>';
        } else {
            $titleSection = '';
        }

        // Text-left
        if ((isset($atts['section_layout']) && $atts['section_layout'] == 'text_left') || (!isset($atts['section_layout']) || $atts['section_layout'] == '')) {
            $wrapper_class = make_section_classes($atts, 'game-section first');
            $html = '
      <section class="' . $wrapper_class . '">
        <div class="container">
          ' . $titleSection . '

          <div class="row">
            <div class="col-md-4">
              <p class="game-section-kicker">' . $position . '</p>
              <h4 class="h4">' . $title . '</h4>
              <p>
                ' . $text . '
              </p>

              <div class="game-section-cta-wrapper">
                <div class="text-center text-md-left">
                  <a href="' . $job_link['href'] . '" class="button dark" title="' . $job_link['text'] . '" target="' . $job_link['target'] . '"><span>' . __('Apply Now') . '</span></a>
                </div>
              </div>

              <div class="game-section-cta-wrapper">
                <div class="text-center text-md-left">
                  <a href="' . $job_site_link['href'] . '" class="button dark" title="' . $job_site_link['text'] . '" target="' . $job_site_link['target'] . '"><span>' . __('Job site') . '</span></a>
                </div>
              </div>
            </div>
            <div class="col-md-5 js-single-slideshow">
              <div class="game-slideshow-wrapper relative">
                <div class="slideshow-current-image js-current-image"></div>
                <div class="slideshow-current-image js-next-image" style="opacity: 0"></div>

                <a href="#" class="slideshow-main-nav left js-slide-prev"></a>
                <a href="#" class="slideshow-main-nav right js-slide-next"></a>

                <img class="game-slideshow" src="' . $firstImage . '" alt="" />
              </div>

              <div class="row">
                <div class="col-md-7 offset-md-5">
                  <div class=" game-slide-indicator-wrapper">
                    <div class="relative">
                      <div
                        class="game-slide-indicator js-slideshow-indicator"
                        data-images="' . $slideshowImages . '"
                      ></div>
                    </div>
                  </div>

                  <p class="d-none d-md-block">' . $quote_text . '</p>
                </div>
              </div>
            </div>
            <div class="col-md-3 d-flex flex-column justify-content-between">
              <div class="game-image-container">
                <img class="game-image" src="' . get_template_directory_uri() . '/assets/images/image-placeholder.png" alt="" />
              </div>

              <div class="game-creator-profile">
                <div class="game-creator-image">
                  ' . $personImage . '
                </div>

                <h3 class="h4">' . $name . '</h3>
                <p>' . $position . '</p>
              </div>

              <p class="d-block d-md-none">' . $quote_text . '</p>
            </div>
          </div>
        </div>
      </section>
      ';
        } else {  // Text-right
            $wrapper_class = make_section_classes($atts, 'game-section');
            $html = '
      <section class="' . $wrapper_class . '">
        <div class="container">
          ' . $titleSection . '
          <div class="row">
            <div class="col-md-3 d-flex flex-column justify-content-between">
              <div class="game-image-container">
                <img class="game-image left" src="' . get_template_directory_uri() . '/assets/images/image-placeholder.png" alt="" />
              </div>

              <div class="game-creator-profile pull">
                <div class="game-creator-image">
                  ' . $personImage . '
                </div>

                <h3 class="h4">' . $name . '</h3>
                <p>' . $position . '</p>
              </div>
            </div>
            <div class="col-md-5 js-single-slideshow">
              <div class="game-slideshow-wrapper relative">
                <div class="slideshow-current-image js-current-image"></div>
                <div class="slideshow-current-image js-next-image" style="opacity: 0"></div>

                <a href="#" class="slideshow-main-nav left js-slide-prev"></a>
                <a href="#" class="slideshow-main-nav right js-slide-next"></a>

                <img class="game-slideshow" src="' . $firstImage . '" alt="" />
              </div>

              <div class="row">
                <div class="col-md-10 offset-md-2">
                  <div class="game-slide-indicator-wrapper">
                    <div class="relative">
                      <div
                        class="game-slide-indicator js-slideshow-indicator"
                        data-images="' . $slideshowImages . '"
                      ></div>
                    </div>
                  </div>

                  <h2 class="h2 game-heading-space-bottom">' . $quote_title . '</h2>
                  <p>
                  ' . $quote_text . '
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <p class="game-section-kicker">' . $position . '</p>
              <h4 class="h4">' . $title . '</h4>
              <p>
              ' . $text . '
              </p>

              <div class="game-section-cta-wrapper">
                <div class="text-center text-md-left">
                  <a href="' . $job_link['href'] . '" class="button dark" title="' . $job_link['text'] . '" target="' . $job_link['target'] . '"><span>' . __('Apply now') . '</span></a>
                </div>
              </div>

              <div class="game-section-cta-wrapper">
                <div class="text-center text-md-left">
                  <a href="' . $job_site_link['href'] . '" class="button dark" title="' . $job_site_link['text'] . '" target="' . $job_site_link['target'] . '"><span>' . __('Job site') . '</span></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      ';
        }

        return $html;
    }
}

new ubisoft_portfolio_section();
