<?php
/**
 * VC Module: Headline
 */

class ubisoft_education_benefits extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_education_benefits', array($this, 'element_frontend'));
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
                'name' => _x('Image & Text columns', 'visualcomposer'),
                'description' => _x('Creates a "Image & Text columns" section". Formerly known as "Education Benefits".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_education_benefits',
                'params' => array(
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
                            ),
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
			<h3>Image & Text columns</h3>
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
          <div class="col-6 col-md-2 studios-feature">
            <div><img src="' . $image . '" alt="" /></div>
            <div>' . $member['text'] . '</div>
          </div>
        ';
            }
        }

        $wrapper_class = make_section_classes($atts, 'about-perks');

        $html = '
    <section class="' . $wrapper_class . '">
      <div class="container">
        <div class="row justify-content-md-center">
          ' . $membersMarkup . '
        </div>
      </div>
    </section>
    ';

        return $html;
    }
}

new ubisoft_education_benefits();
