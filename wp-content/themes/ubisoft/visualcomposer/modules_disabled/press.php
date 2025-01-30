<?php
/**
 * VC Module: Headline
 */

class ubisoft_press extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_press', array($this, 'element_frontend'));
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
                'name' => _x('Press Teaser', 'visualcomposer'),
                'description' => _x('Creates a "Press Teaser" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_press',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Button Text', 'visualcomposer'),
                        'param_name' => 'button_text',
                    ),
                    array(
                        'type' => 'my_link',
                        'heading' => _x('Button Link', 'visualcomposer'),
                        'param_name' => 'my_button_link',
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
                                'heading' => _x('Date', 'visualcomposer'),
                                'param_name' => 'date',
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
			<h3>Press Teaser</h3>
			{{ params.title }}
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
                if (isset($member['link'])) {
                    $link = $member['link'];
                } else {
                    $link = '#';
                }

                $membersMarkup .= '
          <div class="col-md-4 section-press-release">
            <div class="section-press-date">' . $member['date'] . '</div>
            <p>
              ' . $member['title'] . '
            </p>
          </div>
        ';
            }
        }

        $button_link = parse_my_link_vars(empty($atts['my_button_link']) ? null : $atts['my_button_link']);

        $wrapper_class = make_section_classes($atts, 'section-press section-grey');

        $html = '
    <section class="' . $wrapper_class . '">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2 class="h2">' . $atts['title'] . '</h2>
          </div>
        </div>

        <div class="row">
          ' . $membersMarkup . '
        </div>

        <div class="row section-press-cta-row">
          <div class="col-md-12">
            <div class="text-center text-md-left">
              <a href="' . $button_link['href'] . '" class="button dark" title="' . $button_link['text'] . '" target="' . $button_link['target'] . '"><span>' . $atts['button_text'] . '</span></a>
            </div>
          </div>
        </div>
      </div>
    </section>
    ';

        return $html;
    }
}

new ubisoft_press();
