<?php
/**
 * VC Module: Resource Links
 */

class ubisoft_resource_links extends WPBakeryShortCode
{
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_resource_links', array($this, 'element_frontend'));
    }

    public function element_mapping()
    {
        if (!defined('WPB_VC_VERSION')) {
            return;
        }

        vc_map(array(
            'name' => _x('Resource Links', 'visualcomposer'),
            'description' => _x('Display a collection of resource links or cards', 'visualcomposer'),
            'base' => 'ubisoft_resource_links',
            'category' => _x('Content', 'visualcomposer'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => _x('Section Heading', 'visualcomposer'),
                    'param_name' => 'heading',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'param_group',
                    'heading' => _x('Resource Items', 'visualcomposer'),
                    'param_name' => 'resources',
                    'params' => array(
                        array(
                            'type' => 'dropdown',
                            'heading' => _x('Display Type', 'visualcomposer'),
                            'param_name' => 'display_type',
                            'value' => array(
                                'Link' => 'link',
                                'Card' => 'card',
                            ),
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'attach_image',
                            'heading' => _x('Card Image', 'visualcomposer'),
                            'param_name' => 'card_image',
                            'dependency' => array(
                                'element' => 'display_type',
                                'value' => 'card',
                            ),
                            'description' => _x('Select an image for the card', 'visualcomposer'),
                            'settings' => array(
                                'multiple' => false,
                                'max_width' => 1920,
                                'max_height' => 1080,
                            ),
                        ),
                        array(
                            'type' => 'attach_image',
                            'heading' => _x('Link Image', 'visualcomposer'),
                            'param_name' => 'link_image',
                            'dependency' => array(
                                'element' => 'display_type',
                                'value' => 'link',
                            ),
                            'description' => _x('Select an image for the link', 'visualcomposer'),
                            'settings' => array(
                                'multiple' => false,
                                'max_width' => 1000,
                                'max_height' => 1000,
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => _x('Title', 'visualcomposer'),
                            'param_name' => 'title',
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => _x('Description', 'visualcomposer'),
                            'param_name' => 'description',
                            'dependency' => array(
                                'element' => 'display_type',
                                'value' => 'card',
                            ),
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => _x('Information', 'visualcomposer'),
                            'param_name' => 'information',
                            'settings' => array(
                                'max_chars' => 100,
                            ),
                            'dependency' => array(
                                'element' => 'display_type',
                                'value' => 'link',
                            ),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => _x('Link Type', 'visualcomposer'),
                            'param_name' => 'link_type',
                            'value' => array(
                                'Internal Link' => 'internal',
                                'External Link' => 'external',
                                'Download File' => 'download',
                            ),
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'vc_link',
                            'heading' => _x('Link URL', 'visualcomposer'),
                            'param_name' => 'link',
                            'dependency' => array(
                                'element' => 'link_type',
                                'value' => array('internal', 'external'),
                            ),
                        ),
                        array(
                            'type' => 'file_picker',
                            'heading' => _x('Download File', 'visualcomposer'),
                            'param_name' => 'download_file',
                            'dependency' => array(
                                'element' => 'link_type',
                                'value' => 'download',
                            ),
                        ),
                    ),
                ),
            ),
            'admin_enqueue_js' => get_template_directory_uri() . '/assets/backend.js',
            'js_view' => 'CustomElementView',
            'custom_markup' => $this->element_backend(),
        ));
    } /**
    * Element backend.
    * {{ params }} are rendered in get_template_directory_uri() . '/assets/backend.js
    */
    public function element_backend()
    {
        $html = '<div class="module module-headline">
            <h3>Resource Links</h3>
            {{ params.heading }}
        </div>';
        return $html;
    }    

    public function element_frontend($atts, $content = null)
    {
        $atts = vc_map_get_attributes('ubisoft_resource_links', $atts);
        $resources = vc_param_group_parse_atts($atts['resources']);
        
        ob_start();
        ?>
        <div class="container resource-links">

            <?php if (!empty($atts['heading'])) : ?>
                <h2 class="resource-heading"><?php echo esc_html($atts['heading']); ?></h2>
            <?php endif; ?>

            <div class="resource-grid">
                <?php foreach ($resources as $resource) : 
                    $icon_class = $this->get_icon_class($resource['link_type']);
                    $link_url = '';
                    $link_target = '';

                    if ($resource['link_type'] === 'download' && !empty($resource['download_file'])) {
                        $link_url = wp_get_attachment_url($resource['download_file']);
                        $link_target = '_blank';
                    } else if (!empty($resource['link'])) {
                        $link = vc_build_link($resource['link']);
                        $link_url = $link['url'];
                        $link_target = $link['target'];
                    }
                ?>
                    <?php if ($resource['display_type'] === 'card') : ?>

                        <?php if (!empty($link_url)) : ?><a href="<?php echo esc_url($link_url); ?>" <?php echo !empty($link_target) ? 'target="' . esc_attr($link_target) . '"' : ''; ?>><?php endif; ?>
                            <div class="resource-card">
                                <?php if (!empty($resource['card_image'])) : ?>
                                    <div class="resource-image">
                                        <?php echo wp_get_attachment_image($resource['card_image'], 'full'); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="resource-content">
                                    <h3 class="resource-title">
                                        <?php echo esc_html($resource['title']); ?>
                                    </h3>
                                    <?php if (!empty($resource['description'])) : ?>
                                        <div class="resource-description">
                                            <?php echo wp_kses_post($resource['description']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($link_url)) : ?>
                                        <div class="resource-icon">
                                            <i class="<?php echo esc_attr($icon_class); ?>"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php if (!empty($link_url)) : ?></a><?php endif; ?>

                        <?php else : ?>
                        <a href="<?php echo esc_url($link_url); ?>" 
                           <?php echo !empty($link_target) ? 'target="' . esc_attr($link_target) . '"' : ''; ?>
                           <?php echo $resource['link_type'] === 'download' ? 'download' : ''; ?>
                           class="resource-link <?php echo !empty($resource['link_image']) ? 'has-image' : ''; ?>">


                            <?php if (!empty($resource['link_image'])) : ?>
                                <div class="link-image">
                                    <?php echo wp_get_attachment_image($resource['link_image'], 'full'); ?>
                                </div>
                            <?php endif; ?>


                            <div class="link-content">
                                <div class="link-header">
                                    <i class="<?php echo esc_attr($icon_class); ?>"></i>
                                </div>

                                <h3 class="link-title">
                                    <?php echo esc_html($resource['title']); ?>
                                </h3>

                                <div class="link-footer">
                                    <?php if (!empty($resource['information'])) : ?>
                                        <?php echo wp_kses_post($resource['information']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
 
                        </a>
                    <?php endif; ?>

                <?php endforeach; ?>

            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_icon_class($link_type)
    {
        switch ($link_type) {
            case 'external':
                return 'fa-regular fa-arrow-up-right';
            case 'internal':
                return 'fa-regular fa-arrow-right';
            case 'download':
                return 'fa-regular fa-arrow-down-to-line';
            default:
                return 'fa-regular fa-arrow-up-right-from-square';
        }
    }

}

new ubisoft_resource_links(); 