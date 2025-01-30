<?php
/**
 * VC Module: Headline
 */

class ubisoft_benefits extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_benefits', array($this, 'element_frontend'));
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
                'name' => _x('Benefits & Values', 'visualcomposer'),
                'description' => _x('Creates a "Benefits & Values" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_benefits',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Text', 'visualcomposer'),
                        'param_name' => 'text',
                    ),
                    array(
                        'type' => 'param_group',
                        'value' => '',
                        'param_name' => 'members',
                        // Note params is mapped inside param-group:
                        'params' => array(
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Text', 'visualcomposer'),
                                'param_name' => 'text',
                                'admin_label' => true
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
			<h3>Benefits & Values</h3>
			{{ params.title }}<br />
			<br />
			{{ params.text }}
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
                if (isset($member['image_id']) && $member['image_id'] !== '') {
                    $image = wp_get_attachment_image_url($member['image_id'], 'xxl');
                } else {
                    $image = '';
                }

                $membersMarkup .= '
          <div class="jobs-benefit-item">
            <div class="jobs-benefit-item-wrapper">
              <div class="jobs-benefit-image"><img src="' . $image . '" /></div>
              <p>' . $member['text'] . '</p>
            </div>
          </div>
        ';
            }
        }

        $wrapper_class = make_section_classes($atts, 'jobs-benefits');

        $html = '
        <section class="' . $wrapper_class . '">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-md-4">
                <h2 class="h2">' . $atts['title'] . '</h2>
              </div>
              <div class="col-md-7 offset-md-1">
                <p>' . $atts['text'] . '</p>
              </div>
            </div>

            <div class="jobs-benefit-list-container">
              <div class="jobs-benefit-list">
                ' . $membersMarkup . '
              </div>
            </div>
          </div>
        </section>
        ';

        return $html;
    }
}

new ubisoft_benefits();
