<?php
/**
 * VC Module: Headline
 */

class ubisoft_latest_press extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_latest_press', array($this, 'element_frontend'));
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
                'name' => _x('Latest Press Teasers', 'visualcomposer'),
                'description' => _x('Show 3 latest press teasers', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_latest_press',
                'params' => [
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
                ],
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
			<h3>3 Latest Press Teasers</h3>
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $latest_posts = get_posts([
            'posts_per_page' => 3,
            'suppress_filters' => false
        ]);

        if (empty($latest_posts)) {
            return '';
        }

        $teaser_html = [];

        foreach ($latest_posts as $latest_post) {
            $teaser_html[] = '
                <div class="col-md-4 section-press-release">
                    <a href="' . get_the_permalink($latest_post->ID) . '">
                        <div class="section-press-date">' . get_the_time(get_option('date_format'), $latest_post->ID) . '</div>
                        ' . get_field('press_intro', $latest_post->ID) . '
                    </a>
                </div>';
        }

        $wrapper_class = make_section_classes($atts, 'section-press section-grey');

        $html = '
            <section class="' . $wrapper_class . '">
              <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <h2 class="h2">' . __('Press & Media', 'ubisoft') . '</h2>
                  </div>
                </div>
        
                <div class="row">
                  ' . implode('', $teaser_html) . '
                </div>
        
                <div class="row section-press-cta-row">
                  <div class="col-md-12">
                    <div class="text-center text-md-left">
                      <a href="' . get_permalink(get_option('page_for_posts')) . '" class="button dark"><span>' . __('Press & Media', 'ubisoft') . '</span></a>
                    </div>
                  </div>
                </div>
              </div>
            </section>';

        return $html;
    }
}

new ubisoft_latest_press();
