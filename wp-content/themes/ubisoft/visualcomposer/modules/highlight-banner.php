<?php
/**
 * VC Module: Highlight Banner
 */

class ubisoft_highlight_banner extends WPBakeryShortCode
{
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_highlight_banner', array($this, 'element_frontend'));
    }

    public function element_mapping()
    {
        if (!defined('WPB_VC_VERSION')) {
            return;
        }

        vc_map(array(
            'name' => _x('Highlight Banner', 'visualcomposer'),
            'description' => _x('Highlight banner with pattern overlay', 'visualcomposer'),
            'base' => 'ubisoft_highlight_banner',
            'category' => _x('Content', 'visualcomposer'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => _x('Heading', 'visualcomposer'),
                    'param_name' => 'heading',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textarea',
                    'heading' => _x('Text', 'visualcomposer'),
                    'param_name' => 'text',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => _x('Background Color', 'visualcomposer'),
                    'param_name' => 'background_color',
                    'value' => array(
                        'Dark Blue' => 'dark-blue',
                        'Light Blue' => 'light-blue',
                        'Dark Brown' => 'dark-brown',
                        'Light Brown' => 'light-brown',
                    ),
                    'std' => 'dark-blue'
                ),
            ),
            'admin_enqueue_js' => get_template_directory_uri() . '/assets/backend.js',
            'js_view' => 'CustomElementView',
            'custom_markup' => $this->element_backend(), 
        )
    );
    } /**
    * Element backend.
    * {{ params }} are rendered in get_template_directory_uri() . '/assets/backend.js
    */
    public function element_backend()
    {
        $html = '<div class="module module-headline">
            <h3>Highlight Banner</h3>
            {{ params.heading }}<br />
            <br />
            {{ params.text }}
        </div>';
        return $html;
    }    


    public function element_frontend($atts, $content = null)
    {
        $atts = vc_map_get_attributes('ubisoft_highlight_banner', $atts);
        
        ob_start();
        ?>
        <div class="highlight-banner <?php echo esc_attr($atts['background_color']); ?>">
            <div class="highlight-pattern"></div>
            <div class="container">
                <div class="highlight-content">
                    <?php if (!empty($atts['heading'])) : ?>
                        <h2 class="highlight-heading"><?php echo esc_html($atts['heading']); ?></h2>
                    <?php endif; ?>
                    
                    <?php if (!empty($atts['text'])) : ?>
                        <div class="highlight-text"><?php echo wp_kses_post($atts['text']); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

new ubisoft_highlight_banner(); 