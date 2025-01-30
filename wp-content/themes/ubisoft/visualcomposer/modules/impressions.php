<?php
/**
 * VC Module: Headline
 */

class ubisoft_impressions extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_impressions', array($this, 'element_frontend'));
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
                'name' => _x('Image Carousel', 'visualcomposer'),
                'description' => _x('Creates an "Image Carousel" section". Formerly knwon as "Impressions".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_impressions',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'attach_images',
                        'heading' => _x('Images', 'visualcomposer'),
                        'param_name' => 'image',
                    ),
					array(
						'type' => 'textfield',
						'heading' => _x('Interval', 'visualcomposer'),
						'param_name' => 'interval',
						'value' => '5000',
					),
					array(
						'type' => 'dropdown',
						'heading' => _x('Size', 'visualcomposer'),
						'param_name' => 'size',
						'value' => array(
							'Cover' => 'cover',
							'16:9' => '16_9',
						),
						'std' => 'none'
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
			<h3>Image Carousel</h3>
			{{ params.title }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {

        if (!empty($atts['image'])) {
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

        $wrapper_class = make_section_classes($atts, 'game-impressions');

        $title = null;
        if (!empty($atts['title'])) {
            $title = '<div class="container">
                <div class="row">
                    <div class="col-12 col-md-10 col-xl-8 offset-md-1 offset-xl-2">
                        <h2 class="h2 text-center">' . $atts['title'] . '</h2>
                    </div>
                </div>
            </div>';
        }

		$slider_id = wp_rand();

        $html = '
      <section class="' . $wrapper_class . '">
        ' . $title . '
        <div class="slideshow game-slideshow-wide game-slideshow-' . ($atts['size'] ?? '') . ' js-single-slideshow" data-slideshow-interval="' . (!empty($atts['interval']) ? $atts['interval'] : 5000) . '">
            <div class="slideshow-current-image js-current-image"></div>
            <div class="slideshow-current-image js-next-image" style="opacity: 0"></div>

            <div class="container">
            <div class="row">
                <div class="col-md-12 relative">
                <a href="#" class="slideshow-main-nav left js-slide-prev"></a>
                <a href="#" class="slideshow-main-nav right js-slide-next"></a>

                <div
                    class="game-slide-indicator js-slideshow-indicator" data-slideshow-interval="' . (!empty($atts['interval']) ? $atts['interval'] : 5000) . '" id="indicator-' . $slider_id . '"
                    data-images="' . $slideshowImages . '"
                ></div>
                </div>
            </div>
            </div>
        </div>
      </section>
      <style>
    	#indicator-' . $slider_id . ':after {
    		animation-duration: ' . (!empty($atts['interval']) ? $atts['interval'] : 5000) . 'ms;
    	}
	</style>
    ';

        return $html;
    }

}

new ubisoft_impressions();
