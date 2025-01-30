<?php
/**
 * VC Module: Headline
 */

class ubisoft_full_width_teaser extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_full_width_teaser', array($this, 'element_frontend'));
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
                'name' => _x('Full-Width Teaser', 'visualcomposer'),
                'description' => _x('Creates a "Full-Width Teaser" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_full_width_teaser',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
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
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Image for mobile layout', 'visualcomposer'),
                        'param_name' => 'mobile_image_id',
                        'description' => 'Upload an image here and full-width-teaser will be 9:16 format in mobile layout',
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
			<h3>Full-Width Teaser</h3>
			{{ params.title }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $title = checkVCAttribute($atts, 'title');
        $link = parse_my_link_vars(empty($atts['my_link']) ? null : $atts['my_link']);

        $wrapperClass = make_section_classes($atts, 'portfolio-full-width-wrapper');

        $innerClass = "vacancies-full-width portfolio-full-width";
        $innerId = uniqid('fwt-');
        $style = '';
        $styleMobile = '';

        if (isset($atts['image_id']) && $atts['image_id'] !== '') {
            $image = wp_get_attachment_image_url($atts['image_id'], 'xxl');
            if ($image) {
                $style = "#{$innerId} { background-image: url({$image}); }";
            }
        }

        if (isset($atts['mobile_image_id']) && $atts['mobile_image_id'] !== '') {
            $imageMobile = wp_get_attachment_image_url($atts['mobile_image_id'], 'xxl');
            $innerClass .= " has-mobile-layout";
            if ($imageMobile) {
                $styleMobile = "@media screen and (max-width: 767px) { #{$innerId} { background-image: url({$imageMobile}); } }";
            }
        }

        $html = '
        <style type="text/css">
            ' . $style . '
            ' . $styleMobile . '
        </style>
		<div class="' . $wrapperClass . '">
            <section id="' . $innerId . '" class="' . $innerClass . ' portfolio-full-width-' . ($atts['size'] ?? '') . '">
                <div>
                <div class="container">
                    <div class="row">
                    <div class="col-md-6">
                        ' . (!empty($title) ? "<h2 class='h2'><a href='" . $link['href'] . "' title='" . $link['text'] . "' target='" . $link['target'] . "'>$title</a></h2>" : '') . '
                        <div class="text-md-left">
                            <a href="' . $link['href'] . '" class="button" title="' . $link['text'] . '" target="' . $link['target'] . '"><span>' . get_field('read_more', 'option') . '</span></a>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </section>
		</div>
		';

        return $html;
    }
}

new ubisoft_full_width_teaser();
