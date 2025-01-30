<?php
/**
 * VC Module: Community Teaser
 */

class ubisoft_community_teaser extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_community_teaser', array($this, 'element_frontend'));
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
                'name' => _x('Community Teaser', 'visualcomposer'),
                'description' => _x('Community teaser with a CTA and a large image.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_community_teaser',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => _x('Overline', 'visualcomposer'),
                        'param_name' => 'overline',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => _x('Headline', 'visualcomposer'),
                        'param_name' => 'headline',
                    ),
                    array(
                        'type' => 'textarea',
                        'class' => '',
                        'heading' => _X('Text', 'visualcomposer'),
                        'param_name' => 'text',
                    ),
                    array(
                        'type' => 'vc_link',
                        'heading' => _x('Primary Button', 'visualcomposer'),
                        'param_name' => 'primary_button',
                    ),
                    array(
                        'type' => 'vc_link',
                        'heading' => _x('Secondary Button', 'visualcomposer'),
                        'param_name' => 'secondary_button',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Teaser Image', 'visualcomposer'),
                        'param_name' => 'teaser_image',
                    ),
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
			<h3>Community Teaser</h3>
			{{ params.headline }}<br />
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

        $content_html = $this->getContentHTML($atts);
        $wrapper_class = make_section_classes($atts, '');
        return '<div class="' . $wrapper_class . '">' . $content_html . '</div>';
    }

    private function getContentHTML($atts)
    {
        $teaser_image = !empty($atts['teaser_image']) ? wp_get_attachment_image($atts['teaser_image'], 'xxl') : '';

        $buttons = '';
        if (!empty($atts['primary_button'])) {
            $link = vc_build_link($atts['primary_button']);
            $buttons .= '<a href="' . ($link['url'] ?? '') . '" class="button" ' . ($link['target'] !== '' ? 'target="' . $link['target'] . '"' : '') . ' ' . ($link['rel'] !== '' ? 'rel="' . $link['rel'] . '"' : '') . '>' . ($link['title'] ?? '') . '</a>';
        }
        if (!empty($atts['secondary_button'])) {
            $link = vc_build_link($atts['secondary_button']);
            $buttons .= '<a href="' . ($link['url'] ?? '') . '" class="button outline" ' . ($link['target'] !== '' ? 'target="' . $link['target'] . '"' : '') . ' ' . ($link['rel'] !== '' ? 'rel="' . $link['rel'] . '"' : '') . '>' . ($link['title'] ?? '') . '</a>';
        }

        return '
            <div class="community-teaser">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            ' . (!empty($atts['overline']) ? '<p><strong>' . $atts['overline'] . '</strong></p>' : '') . '
                            ' . (!empty($atts['headline']) ? '<h2 class="h2 smaller">' . $atts['headline'] . '</h2>' : '') . '
                            ' . (!empty($atts['text']) ? '<p>' . $atts['text'] . '</p>' : '') . '
                            <div class="community-teaser__buttons">
                                    ' . $buttons . '
                                </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="community-teaser__image">
                                ' . $teaser_image . '
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }
}

new ubisoft_community_teaser();
