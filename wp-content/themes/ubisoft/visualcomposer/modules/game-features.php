<?php
/**
 * VC Module: Headline
 */

class ubisoft_game_features extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_game_features', array($this, 'element_frontend'));
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
                'name' => _x('Game Features', 'visualcomposer'),
                'description' => _x('A list of game features in two columns.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_game_features',
                'params' => array(
                    array(
                        'type' => 'param_group',
                        'value' => '',
                        'param_name' => 'features',
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'heading' => _x('Title', 'visualcomposer'),
                                'param_name' => 'title',
                                'admin_label' => true
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Copy', 'visualcomposer'),
                                'param_name' => 'copy',
                            ),
                            array(
                                'type' => 'vc_link',
                                'heading' => _x('Primary Button', 'visualcomposer'),
                                'param_name' => 'primary_button',
                            ),
                        )
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
			<h3>Game Features</h3>
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
        $items = '';
        $features = empty($atts['features']) ? [] : vc_param_group_parse_atts($atts['features']);
        foreach ($features as $feature) {
            $items .= '<div class="game-features__item">
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                    <g id="Group_1" data-name="Group 1" transform="translate(0 0)">
                        <path id="Path_1" data-name="Path 1" d="M30.543,9.451a2.358,2.358,0,0,0-3.334,0l-1.531,1.53A27.684,27.684,0,0,1,20,0a27.683,27.683,0,0,1-5.679,10.987L12.791,9.451a2.358,2.358,0,0,0-3.335,3.335l1.531,1.531A27.689,27.689,0,0,1,0,19.995a27.683,27.683,0,0,1,10.987,5.679L9.456,27.2a2.358,2.358,0,0,0,3.335,3.335l1.531-1.531A27.682,27.682,0,0,1,20,40a27.689,27.689,0,0,1,5.679-10.987l1.531,1.531A2.358,2.358,0,0,0,30.544,27.2l-1.531-1.531A27.683,27.683,0,0,1,40,20a27.684,27.684,0,0,1-10.987-5.679l1.531-1.531a2.357,2.357,0,0,0,0-3.335M23.92,19.41a7.59,7.59,0,0,0,1.549.585A7.607,7.607,0,0,0,20,25.464a7.619,7.619,0,0,0-1.99-3.481,7.58,7.58,0,0,0-3.48-1.989A7.62,7.62,0,0,0,20,14.526a7.632,7.632,0,0,0,3.92,4.884" transform="translate(0 0.005)" fill="currentColor"/>
                    </g>
                    </svg>

                </p>
                ' . (!empty($feature['title']) ? '<h3 class="h4">' . $feature['title'] . '</h3>' : '') . '
                ' . (!empty($feature['copy']) ? '<p>' . $feature['copy'] . '</p>' : '') . '
            </div>';
        }

        return '
            <div class="game-features">
                <div class="container">
                    <div class="game-features__wrapper">
                        ' . $items . '
                    </div>
                </div>
            </div>';
    }
}

new ubisoft_game_features();
