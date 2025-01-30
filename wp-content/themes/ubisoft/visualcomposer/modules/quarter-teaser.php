<?php
/**
 * VC Module: Headline
 */

class ubisoft_quarter_teaser extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_quarter_teaser', array($this, 'element_frontend'));
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
                'name' => _x('Quarter Teaser', 'visualcomposer'),
                'description' => _x('Creates a "Quarter Teaser" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_quarter_teaser',
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
                        'type' => 'textarea',
                        'heading' => _x('Title 3', 'visualcomposer'),
                        'param_name' => 'title_3',
                    ),
                    array(
                        'type' => 'my_link',
                        'heading' => _x('Link 3', 'visualcomposer'),
                        'param_name' => 'my_link_3',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Image 3', 'visualcomposer'),
                        'param_name' => 'image_id_3',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title 4', 'visualcomposer'),
                        'param_name' => 'title_4',
                    ),
                    array(
                        'type' => 'my_link',
                        'heading' => _x('Link 4', 'visualcomposer'),
                        'param_name' => 'my_link_4',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Image 4', 'visualcomposer'),
                        'param_name' => 'image_id_4',
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
			<h3>Quarter Teaser</h3>
			{{ params.title_1 }}<br />
			{{ params.title_2 }}<br />
			{{ params.title_3 }}<br />
			{{ params.title_4 }}
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
        if (isset($atts['image_id_3']) && $atts['image_id_3'] !== '') {
            $image_3 = wp_get_attachment_image_url($atts['image_id_3'], 'xxl');
        } else {
            $image_3 = '';
        }
        if (isset($atts['image_id_4']) && $atts['image_id_4'] !== '') {
            $image_4 = wp_get_attachment_image_url($atts['image_id_4'], 'xxl');
        } else {
            $image_4 = '';
        }

        $title_1 = checkVCAttribute($atts, 'title_1');
        $title_2 = checkVCAttribute($atts, 'title_2');
        $title_3 = checkVCAttribute($atts, 'title_3');
        $title_4 = checkVCAttribute($atts, 'title_4');

        $link_1 = parse_my_link_vars(empty($atts['my_link_1']) ? null : $atts['my_link_1']);
        $link_2 = parse_my_link_vars(empty($atts['my_link_2']) ? null : $atts['my_link_2']);
        $link_3 = parse_my_link_vars(empty($atts['my_link_3']) ? null : $atts['my_link_3']);
        $link_4 = parse_my_link_vars(empty($atts['my_link_4']) ? null : $atts['my_link_4']);

        $wrapper_class = make_section_classes($atts, 'section-list relative portfolio-games-list');

        $html = '
      <section class="' . $wrapper_class . '">
        <div class="container">
          <div class="row">
            <div class="section-item col-md-3 zoom-out-teaser">
              <div class="zoom-outer-img-container img-shadow">
                <a href="' . $link_1['href'] . '" title="' . $link_1['text'] . '" target="' . $link_1['target'] . '"><img src="' . $image_1 . '" alt="' . $link_1['text'] . '" target="" /></a>
              </div>
              <h2 class="h3 is-underlined"><a href="' . $link_1['href'] . '" title="' . $link_1['text'] . '" target="' . $link_1['target'] . '">' . $title_1 . '</a></h2>
              <a href="' . $link_1['href'] . '" class="button dark" title="' . $link_1['text'] . '" target="' . $link_1['target'] . '"><span>' . get_field('read_more', 'option') . '</span></a>
            </div>

            <div class="section-item col-md-3 zoom-out-teaser">
              <div class="zoom-outer-img-container img-shadow">
                <a href="' . $link_2['href'] . '" title="' . $link_2['text'] . '" target="' . $link_2['target'] . '"><img src="' . $image_2 . '" alt="' . $link_2['text'] . '" /></a>
              </div>
              <h2 class="h3 is-underlined"><a href="' . $link_2['href'] . '" title="' . $link_2['text'] . '" target="' . $link_2['target'] . '">' . $title_2 . '</a></h2>
              <a href="' . $link_2['href'] . '" class="button dark" title="' . $link_2['text'] . '" target="' . $link_2['target'] . '"><span>' . get_field('read_more', 'option') . '</span></a>
            </div>

            <div class="section-item  col-md-3 zoom-out-teaser">
              <div class="zoom-outer-img-container img-shadow">
                <a href="' . $link_3['href'] . '" title="' . $link_3['text'] . '" target="' . $link_3['target'] . '"><img src="' . $image_3 . '" alt="' . $link_3['text'] . '" /></a>
              </div>
              <h2 class="h3 is-underlined"><a href="' . $link_3['href'] . '" title="' . $link_3['text'] . '" target="' . $link_3['target'] . '">' . $title_3 . '</a></h2>
              <a href="' . $link_3['href'] . '" class="button dark" title="' . $link_3['text'] . '" target="' . $link_3['target'] . '"><span>' . get_field('read_more', 'option') . '</span></a>
            </div>

            <div class="section-item  col-md-3 zoom-out-teaser">
              <div class="zoom-outer-img-container img-shadow">
                <a href="' . $link_4['href'] . '" title="' . $link_4['text'] . '" target="' . $link_4['target'] . '"><img src="' . $image_4 . '" alt="' . $link_4['text'] . '" /></a>
              </div>
              <h2 class="h3 is-underlined"><a href="' . $link_4['href'] . '" title="' . $link_4['text'] . '" target="' . $link_4['target'] . '">' . $title_4 . '</a></h2>
              <a href="' . $link_4['href'] . '" class="button dark" title="' . $link_4['text'] . '" target="' . $link_4['target'] . '"><span>' . get_field('read_more', 'option') . '</span></a>
            </div>
          </div>
        </div>
      </section>
    ';

        return $html;
    }
}

new ubisoft_quarter_teaser();
