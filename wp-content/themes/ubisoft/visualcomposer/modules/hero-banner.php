<?php
/**
 * VC Module: Hero Banner
 */

class ubisoft_hero_banner extends WPBakeryShortCode
{
    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_hero_banner', array($this, 'element_frontend'));
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
                'name' => _x('Hero Banner', 'visualcomposer'),
                'description' => _x('Hero banner with background image/video and content.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_hero_banner',
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Background Type', 'visualcomposer'),
                        'param_name' => 'background_type',
                        'value' => array(
                            'Image' => 'image',
                            'Video' => 'video',
                        ),
                        'std' => 'image'
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Background Image', 'visualcomposer'),
                        'param_name' => 'background_image',
                        'dependency' => array(
                            'element' => 'background_type',
                            'value' => 'image',
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => _x('Background Video URL', 'visualcomposer'),
                        'param_name' => 'background_video',
                        'description' => _x('Enter MP4 video URL', 'visualcomposer'),
                        'dependency' => array(
                            'element' => 'background_type',
                            'value' => 'video',
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Enable Gradient Overlay', 'visualcomposer'),
                        'param_name' => 'enable_gradient',
                        'value' => array(_x('Yes', 'visualcomposer') => 'yes'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Content Alignment', 'visualcomposer'),
                        'param_name' => 'content_align',
                        'value' => array(
                            'Left' => 'left',
                            'Center' => 'center',
                        ),
                        'std' => 'left'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => _x('Heading', 'visualcomposer'),
                        'param_name' => 'heading',
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Description', 'visualcomposer'),
                        'param_name' => 'description',
                    ),
                    array(
                        'type' => 'vc_link',
                        'heading' => _x('Button', 'visualcomposer'),
                        'param_name' => 'button',
                        'description' => _x('Add a call to action button', 'visualcomposer'),
                    ),
                ),
                'js_view' => 'VcSectionView',
            )
        );
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $atts = vc_map_get_attributes('ubisoft_hero_banner', $atts);
        
        // Get background
        $background = '';
        if ($atts['background_type'] === 'image' && !empty($atts['background_image'])) {
            $image_url = wp_get_attachment_image_url($atts['background_image'], 'full');
            $background = 'style="background-image: url(' . esc_url($image_url) . ')"';
        }

        // Parse button
        $button = vc_build_link($atts['button']);
        
        // Build classes
        $classes = array('hero-banner');
        if ($atts['enable_gradient'] === 'yes') {
            $classes[] = 'has-gradient';
        }
        if ($atts['content_align'] === 'center') {
            $classes[] = 'content-center';
        }
        
        // Start output
        ob_start();
        ?>
        <div class="<?php echo esc_attr(implode(' ', $classes)); ?>" <?php echo $background; ?>>
            <?php if ($atts['background_type'] === 'video' && !empty($atts['background_video'])) : ?>
                <video class="hero-video" autoplay muted loop playsinline>
                    <source src="<?php echo esc_url($atts['background_video']); ?>" type="video/mp4">
                </video>
            <?php endif; ?>
            
            <div class="hero-content">
                <?php if (!empty($atts['heading'])) : ?>
                    <h1 class="hero-heading"><?php echo esc_html($atts['heading']); ?></h1>
                <?php endif; ?>
                
                <?php if (!empty($atts['description'])) : ?>
                    <div class="hero-description"><?php echo wp_kses_post($atts['description']); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($button['url'])) : ?>
                    <a href="<?php echo esc_url($button['url']); ?>" 
                       class="hero-button"
                       title="<?php echo esc_attr($button['title']); ?>"
                       <?php echo !empty($button['target']) ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
                        <?php echo esc_html($button['title']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize the class
new ubisoft_hero_banner(); 