<?php
/**
 * VC Module: Game Hero
 */

class ubisoft_game_hero extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_game_hero', array($this, 'element_frontend'));
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
                'name' => _x('Game Hero', 'visualcomposer'),
                'description' => _x('Game teaser with two CTAs and a large image.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_game_hero',
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Style', 'visualcomposer'),
                        'param_name' => 'style',
                        'value' => array(
                            'Default' => '',
                            'Anno 117' => 'anno117',
                        ),
                        'std' => ''
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Background Color', 'visualcomposer'),
                        'param_name' => 'background_color',
                        'value' => array(
                            'None' => '',
                            'Plum' => 'plum',
                            'Scroll' => 'scroll',
                            'Stone' => 'stone',
                        ),
                        'std' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'textarea',
                        'class' => '',
                        'heading' => _X('Text', 'visualcomposer'),
                        'param_name' => 'text',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Logo Image', 'visualcomposer'),
                        'param_name' => 'logo_image',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Teaser Image', 'visualcomposer'),
                        'param_name' => 'teaser_image',
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
			<h3>Game Hero</h3>
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
        $content_html = $this->getContentHTML($atts);
        $wrapper_class = make_section_classes($atts, '');
        return '<div class="' . $wrapper_class . '">' . $content_html . '</div>';
    }

    private function getContentHTML($atts)
    {
        $style = !empty($atts['style']) ? $atts['style'] : 'default';
        $logo_image = !empty($atts['logo_image']) ? wp_get_attachment_image($atts['logo_image'], 'xxl') : '';
        $teaser_image = !empty($atts['teaser_image']) ? wp_get_attachment_image($atts['teaser_image'], 'xxl') : '';
        $background_color = !empty($atts['background_color']) ? 'bg-' . $atts['background_color'] : '';

        $buttons = '';
        if (!empty($atts['primary_button'])) {
            $link = vc_build_link($atts['primary_button']);
            $buttons .= '<a href="' . ($link['url'] ?? '') . '" class="button dark" ' . ($link['target'] !== '' ? 'target="' . $link['target'] . '"' : '') . ' ' . ($link['rel'] !== '' ? 'rel="' . $link['rel'] . '"' : '') . '>' . ($link['title'] ?? '') . '</a>';
        }
        if (!empty($atts['secondary_button'])) {
            $link = vc_build_link($atts['secondary_button']);
            $buttons .= '<a href="' . ($link['url'] ?? '') . '" class="button dark outline" ' . ($link['target'] !== '' ? 'target="' . $link['target'] . '"' : '') . ' ' . ($link['rel'] !== '' ? 'rel="' . $link['rel'] . '"' : '') . '>' . ($link['title'] ?? '') . '</a>';
        }

        return '
            <div class="game-hero game-hero--' . $style . ' ' . $background_color . '">
                <div class="container-fluid no-padding">
                    <div class="game-hero__wrapper">
                        <div class="game-hero__content">
                            ' . $logo_image . '
                            <div class="game-hero__copy">
                                ' . (!empty($atts['title']) ? '<h2 class="h4">' . $atts['title'] . '</h2>' : '') . '
                                ' . (!empty($atts['text']) ? '<p>' . $atts['text'] . '</p>' : '') . '
                                <div class="game-hero__buttons">
                                    ' . $buttons . '
                                </div>
                            </div>
                        </div>
                        <div class="game-hero__image">
                            ' . $teaser_image . '
                        </div>
                    </div>
                </div>
            </div>';
    }
}

new ubisoft_game_hero();
