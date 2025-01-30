<?php
/**
 * VC Module: Headline
 */

class ubisoft_recruiters extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_recruiters', array($this, 'element_frontend'));
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
                'name' => _x('Recruiters', 'visualcomposer'),
                'description' => _x('Creates a "Recruiters" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_recruiters',
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
                        'param_name' => 'recruiters',
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
                                'heading' => _x('Text', 'visualcomposer'),
                                'param_name' => 'text',
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Button Text', 'visualcomposer'),
                                'param_name' => 'button_text',
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Button Link', 'visualcomposer'),
                                'param_name' => 'button_link',
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
			<h3>Recruiters</h3>
			{{ params.title }}<br />
			{{ params.text }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        if (isset($atts['recruiters'])) {
            $recruiters = vc_param_group_parse_atts($atts['recruiters']);
        }

        $recruitersMarkup = '';

        if (count($recruiters) > 0) {
            foreach ($recruiters as $index => $recruiter) {
                // Don't bother rendering if there is no data set
                if (count($recruiter) < 1) {
                    continue;
                }

                if (isset($recruiter['image_id']) && $recruiter['image_id'] !== '') {
                    $image = wp_get_attachment_image_url($recruiter['image_id'], 'xxl');
                } else {
                    $image = '';
                }

                $name = checkVCAttribute($recruiter, 'name');
                $text = checkVCAttribute($recruiter, 'text');
                $button_link = checkVCAttribute($recruiter, 'button_link');
                $button_text = checkVCAttribute($recruiter, 'button_text');

                $recruitersMarkup .= '
                  <div class="col-md-4">'
                    . (!empty($button_link) && !empty($button_text) ? "<a href='$button_link'>" : '')
                    . (!empty($image) ? "<img src='$image' alt='$name' class='img-shadow' />" : '')
                    . (!empty($button_link) && !empty($button_text) ? "</a>" : '')

                    . (!empty($name) ? "<h3 class='h3 is-underlined'>" : '')
                    . (!empty($button_link) && !empty($button_text) ? "<a href='$button_link'>" : '')
                    . (!empty($name) ? "$name" : '')
                    . (!empty($button_link) && !empty($button_text) ? "</a>" : '')
                    . (!empty($name) ? "</h3>" : '')

                    . (!empty($text) ? "<p>$text</p>" : '')
                    . (!empty($button_link) && !empty($button_text) ? "<a href='$button_link' class='button dark outline' style='margin-bottom: 20px'>$button_text</a>" : '') . '
                  </div>
                ';
            }
        }

        $title = checkVCAttribute($atts, 'title', __('Recruiters', 'visualcomposer'));
        $text = checkVCAttribute($atts, 'text');

        $wrapper_class = make_section_classes($atts, 'about-us-recruiters');

        $html = '
      <section class="' . $wrapper_class . '">

        <div class="container">
          <div class="row about-us-recruiters-heading">
            <div class="col-md-4">
              <h2 class="h2">' . $title . '</h2>
            </div>

            <div class="col-md-4">
              <p>
              ' . $text . '
              </p>
            </div>
          </div>

          <div class="row about-us-recruiters-columns">
            ' . $recruitersMarkup . '
          </div>
        </div>
      </section>
    ';

        return $html;
    }
}

new ubisoft_recruiters();
