<?php
/**
 * VC Module: Headline
 */

class ubisoft_members extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_members', array($this, 'element_frontend'));
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
                'name' => _x('Team Members', 'visualcomposer'),
                'description' => _x('Creates a "Team Members" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_members',
                'params' => array(
                    array(
                        'type' => 'param_group',
                        'value' => '',
                        'param_name' => 'members',
                        // Note params is mapped inside param-group:
                        'params' => array(
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Name', 'visualcomposer'),
                                'param_name' => 'name',
                                'admin_label' => true
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Position', 'visualcomposer'),
                                'param_name' => 'position',
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Favourite Places Column 1', 'visualcomposer'),
                                'param_name' => 'favourite_places_1',
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Favourite Places Column 2', 'visualcomposer'),
                                'param_name' => 'favourite_places_2',
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
			<h3>Team Members</h3>
			{{ params.name }}<br />
			{{ params.position }}
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

                $favourite_places_1 = '';
                if (!empty($member['favourite_places_1'])) {
                    $member['favourite_places_1'] = explode("\n", $member['favourite_places_1']);
                    foreach ($member['favourite_places_1'] as $index => $place) {
                        $favourite_places_1 .= '<div>' . $place . '</div>';
                    }
                }

                $favourite_places_2 = '';
                if (!empty($member['favourite_places_2'])) {
                    $member['favourite_places_2'] = explode("\n", $member['favourite_places_2']);
                    foreach ($member['favourite_places_2'] as $index => $place) {
                        $favourite_places_2 .= '<div>' . $place . '</div>';
                    }
                }

                $membersMarkup .= '
          <div class="col-md-4">
            <a href="#"><img src="' . $image . '" alt=""/></a>

            <h4 class="h4">' . $member['name'] . '</h4>
            <p class="about-us-people-title">' . $member['position'] . '</p>

            <div class="cta berlin">' . get_field('favourite_places', 'option') . '</div>

            <div class="row about-us-people-places">
              <div class="col-6">
                ' . $favourite_places_1 . '
              </div>

              <div class="col-6">
                ' . $favourite_places_2 . '
              </div>
            </div>
          </div>
        ';
            }
        }

        $wrapper_class = make_section_classes($atts, 'about-us-people');

        $html = '
      <section class="' . $wrapper_class . '">
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

new ubisoft_members();
