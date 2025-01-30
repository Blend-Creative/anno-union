<?php
/**
 * VC Module: Headline
 */

class ubisoft_studio_half extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_studio_half', array($this, 'element_frontend'));
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
                'name' => _x('Text & Title with Image', 'visualcomposer'),
                'description' => _x('Creates a "Text & Title with Image" section". Formerly known as "Studio Half".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_studio_half',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Subtitle', 'visualcomposer'),
                        'param_name' => 'sub_title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Text', 'visualcomposer'),
                        'param_name' => 'text',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => _x('Button 1 Text', 'visualcomposer'),
                        'param_name' => 'button_text',
                    ),
                    array(
                        'type' => 'my_link',
                        'heading' => _x('Button 1 Link', 'visualcomposer'),
                        'param_name' => 'my_button_link',
                    ),
					array(
						'type' => 'textfield',
						'heading' => _x('Button 2 Text', 'visualcomposer'),
						'param_name' => 'button_text_2',
					),
					array(
						'type' => 'my_link',
						'heading' => _x('Button 2 Link', 'visualcomposer'),
						'param_name' => 'my_button_link_2',
					),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Layout', 'visualcomposer'),
                        'param_name' => 'layout',
                        'value' => array(
                            'Text Right, Image Left' => 'text_right_image_left',
                            'Text Left, Image Right' => 'text_left_image_right'
                        ),
                        'std' => ''
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Background Image', 'visualcomposer'),
                        'param_name' => 'image_id',
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
			<h3>Text & Title with Image</h3>
			{{ params.title }}<br />
			{{ params.sub_title }}<br />
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
        if (!isset($atts['title'])) {
            $atts['title'] = '';
        }
        if (!isset($atts['sub_title'])) {
            $atts['sub_title'] = '';
        }
        if (!isset($atts['text'])) {
            $atts['text'] = '';
        }
        if (!isset($atts['button_text'])) {
            $atts['button_text'] = '';
        }

        $button_link = parse_my_link_vars(empty($atts['my_button_link']) ? null : $atts['my_button_link']);
		$button_link_2 = parse_my_link_vars(empty($atts['my_button_link_2']) ? null : $atts['my_button_link_2']);

        $specialContent = '';
        if (isset($atts['image_id'])) {
            if (!empty($atts['button_text'])) {
                $specialContent .= '<a href="' . $button_link['href'] . '" title="' . $button_link['text'] . '" target="' . $button_link['target'] . '">';
            }
            $specialContent .= '<img src="' . wp_get_attachment_image_url($atts['image_id'], 'xxl') . '" alt="' . $atts['title'] . '" class="img-shadow" />';
            if (!empty($atts['button_text'])) {
                $specialContent .= '</a>';
            }
        }

        $link_html = empty($atts['button_text']) ?
            "" :
            '<div class="text-center text-md-left">
                    <a href="' . $button_link['href'] . '" class="button dark" title="' . $button_link['text'] . '" target="' . $button_link['target'] . '"><span>' . $atts['button_text'] . '</span></a>
                </div>';

		$link_html_2 = empty($atts['button_text_2']) ?
			"" :
			'<div class="text-center text-md-left">
                    <a href="' . $button_link_2['href'] . '" class="button dark" title="' . $button_link_2['text'] . '" target="' . $button_link_2['target'] . '"><span>' . $atts['button_text_2'] . '</span></a>
                </div>';

        $headline = $atts['title'];
        if (isset($atts['image_id'])) {
            if (!empty($atts['button_text'])) {
                $headline = '<a href="' . $button_link['href'] . '" title="' . $button_link['text'] . '" target="' . $button_link['target'] . '">' . $headline . '</a>';
            }
        }

        if (!isset($atts['layout']) || $atts['layout'] == 'text_right_image_left') {
            $wrapper_class = make_section_classes($atts, 'studios-studio');
            $html = '
				<section class="' . $wrapper_class . '">
					<div class="container">
						<div class="row align-items-center">
							<div class="col-md-7">
								' . $specialContent . '
							</div>

							<div class="col-md-4 offset-md-1">
								<h2 class="h2 is-underlined">' . $headline . '</h2>
								<h3 class="h4">' . $atts['sub_title'] . '</h3>
								<p>
									' . $atts['text'] . '
								</p>
								<div class="button-group">
                           	    	' . $link_html . '
                            	    ' . $link_html_2 . '
                            	</div>
							</div>
						</div>
					</div>
				</section>
			';
        } else {
            $wrapper_class = make_section_classes($atts, 'studios-studio');
            $html = '
				<section class="' . $wrapper_class . '">
					<div class="container">
						<div class="row align-items-center">
							<div class="col-md-4">
								<h2 class="h2 is-underlined">' . $headline . '</h2>
								<h3 class="h4">' . $atts['sub_title'] . '</h3>
								<p>
								' . $atts['text'] . '
                                </p>
                                ' . $link_html . '
							</div>
							<div class="col-md-7 offset-md-1 order-first order-md-last">
								' . $specialContent . '
							</div>
						</div>
					</div>
				</section>
			';
        }

        return $html;
    }
}

new ubisoft_studio_half();
