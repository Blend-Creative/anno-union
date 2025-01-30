<?php
/**
 * VC Module: Headline
 */

class ubisoft_page_teaser extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_page_teaser', array($this, 'element_frontend'));
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
                'name' => _x('Page Teaser', 'visualcomposer'),
                'description' => _x('Creates a "Page Teaser" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_page_teaser',
                'params' => array(
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
                                'heading' => _x('Link', 'visualcomposer'),
                                'param_name' => 'my_link',
                            ),
                            array(
                                'type' => 'attach_image',
                                'heading' => _x('Image', 'visualcomposer'),
                                'param_name' => 'image_id',
                            ),
                            array(
                                'type' => 'checkbox',
                                'heading' => _x('Zoom-out effect', 'visualcomposer'),
                                'param_name' => 'has_zoom_out',
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
			<h3>Page Teaser</h3>
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        if (isset($atts['members'])) {
            $members = vc_param_group_parse_atts($atts['members']);
        }

        $membersMarkup = '';

        if (count($members) > 0) {
            foreach ($members as $index => $member) {
                $classNames = [
                    'section-item',
                    'col-md-4'
                ];

                $link = parse_my_link_vars(empty($member['my_link']) ? null : $member['my_link']);

                if (isset($member['image_id']) && $member['image_id'] !== '') {
                    $image = wp_get_attachment_image_url($member['image_id'], 'xxl');
                } else {
                    $image = '';
                }
                $img = '<a href="' . $link['href'] . '" title="' . $link['text'] . '" target="' . $link['target'] . '"><img src="' . $image . '" alt="' . $link['text'] . '" class="img-shadow" /></a>';

                if (isset($member['has_zoom_out']) && $member['has_zoom_out']) {
                    $classNames[] = 'zoom-out-teaser';
                    $img = '<div class="zoom-outer-img-container img-shadow">' . $img . '</div>';
                }


                $membersMarkup .= '
          <div class="' . implode(' ', $classNames) . '">
            ' . $img . '
            <h2 class="h3 is-underlined"><a href="' . $link['href'] . '" title="' . $link['text'] . '" target="' . $link['target'] . '">' . $member['title'] . '</a></h2>
            <div class="text-center text-md-left">
              <a href="' . $link['href'] . '" class="button dark" title="' . $link['text'] . '" target="' . $link['target'] . '"><span>' . get_field('read_more', 'option') . '</span></a>
            </div>
          </div>
        ';
            }
        }

        $wrapper_class = make_section_classes($atts, 'section-list relative');

        $html = '
      <section class="' . $wrapper_class . '">
        <div class="section-grey-bg bottom"></div>

        <div class="container">
          <div class="row">
            ' . $membersMarkup . '
          </div>
        </div>
      </section>
    ';

        return $html;
    }
}

new ubisoft_page_teaser();
