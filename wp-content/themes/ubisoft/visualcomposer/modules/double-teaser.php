<?php
/**
 * VC Module: Headline
 */

class ubisoft_double_teaser extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_double_teaser', array($this, 'element_frontend'));
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
                'name' => _x('Double Teaser', 'visualcomposer'),
                'description' => _x('Creates a "Double Teaser" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_double_teaser',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title 1', 'visualcomposer'),
                        'param_name' => 'title_1',
                    ),
                    array(
                        'type' => 'my_link',
                        'heading' => _x('Link 1', 'visualcomposer'),
                        'param_name' => 'my_link_1',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Image 1', 'visualcomposer'),
                        'param_name' => 'image_id_1',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title 2', 'visualcomposer'),
                        'param_name' => 'title_2',
                    ),
                    array(
                        'type' => 'my_link',
                        'heading' => _x('Link 2', 'visualcomposer'),
                        'param_name' => 'my_link_2',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Image 2', 'visualcomposer'),
                        'param_name' => 'image_id_2',
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
			<h3>Double Teaser</h3>
			{{ params.title_1 }}<br />
			{{ params.title_2 }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        if (isset($atts['image_id_1']) && $atts['image_id_1'] !== '') {
            $image_1 = wp_get_attachment_image_url($atts['image_id_1'], 'xxl');
        } else {
            $image_1 = '';
        }
        if (isset($atts['image_id_2']) && $atts['image_id_2'] !== '') {
            $image_2 = wp_get_attachment_image_url($atts['image_id_2'], 'xxl');
        } else {
            $image_2 = '';
        }

        $link_1 = parse_my_link_vars(empty($atts['my_link_1']) ? null : $atts['my_link_1']);
        $link_2 = parse_my_link_vars(empty($atts['my_link_2']) ? null : $atts['my_link_2']);

        $wrapper_class = make_section_classes($atts, 'portfolio-featured');

        $html = '
        <section class="' . $wrapper_class . '">
          <div class="container">
            <div class="row">
              <div class="col-md-6">
                <a href="' . $link_1['href'] . '" class="portfolio-featured-game zoom-out-teaser" title="' . $link_1['text'] . '" target="' . $link_1['target'] . '">
                  <div class="zoom-outer-img-container img-shadow">
                    <img src="' . $image_1 . '" alt="" />
                  </div>
                  <div class="portfolio-featured-game-text">
                    <h2 class="h3">' . $atts['title_1'] . '</h2>
                    <span class="button"><span>' . get_field('read_more', 'option') . '</span></span>
                  </div>
                </a>
              </div>

              <div class="col-md-6">
                <a href="' . $link_2['href'] . '" class="portfolio-featured-game zoom-out-teaser" title="' . $link_2['text'] . '" target="' . $link_2['target'] . '">
                  <div class="zoom-outer-img-container img-shadow">
                    <img src="' . $image_2 . '" alt="" />
                  </div>
                  <div class="portfolio-featured-game-text">
                    <h2 class="h3">' . $atts['title_2'] . '</h2>
                    <span class="button"><span>' . get_field('read_more', 'option') . '</span></span>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </section>
      ';

        return $html;
    }
}

new ubisoft_double_teaser();
