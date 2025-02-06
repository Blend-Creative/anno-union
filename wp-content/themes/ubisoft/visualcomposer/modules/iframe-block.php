<?php
/**
 * VC Module: iFrame Block
 */

class ubisoft_iframe_block extends WPBakeryShortCode
{
    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('vc_iframe_block', array($this, 'element_frontend'));
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

        // Define allowed platforms
        $allowed_platforms = array(
            'Select Platform' => '',  // Add a default option
            'YouTube' => 'youtube',
            'Twitch' => 'twitch',
        );

        // Map the block with vc_map()
        vc_map(array(
            'name' => _x('iFrame Block', 'visualcomposer'),
            'description' => _x('Embed videos from YouTube or Twitch', 'visualcomposer'),
            'category' => _x('Content', 'visualcomposer'),
            'base' => 'vc_iframe_block',
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => _x('Platform', 'visualcomposer'),
                    'param_name' => 'platform',
                    'value' => $allowed_platforms,
                    'description' => _x('Select the platform for the embed', 'visualcomposer'),
                    'admin_label' => true,
                    'std' => 'youtube'  
                ),
                array(
                    'type' => 'textfield',
                    'heading' => _x('Video URL', 'visualcomposer'),
                    'param_name' => 'video_url',
                    'description' => _x('Enter the video URL or ID', 'visualcomposer'),
                    'admin_label' => true
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => _x('Aspect Ratio', 'visualcomposer'),
                    'param_name' => 'aspect_ratio',
                    'value' => array(
                        '16:9' => '16:9',
                        '4:3' => '4:3',
                        '1:1' => '1:1',
                    ),
                    'std' => '16:9'
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => _x('Use Container', 'visualcomposer'),
                    'param_name' => 'use_container',
                    'value' => array(_x('Yes', 'visualcomposer') => 'yes'),
                    'description' => _x('Wrap iframe in a container with default max-width', 'visualcomposer'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => _x('Width', 'visualcomposer'),
                    'param_name' => 'width',
                    'description' => _x('Enter width in pixels or percentage (e.g., 100% or 600px)', 'visualcomposer'),
                    'value' => '100%'
                ),
                array(
                    'type' => 'textfield',
                    'heading' => _x('Maximum Width', 'visualcomposer'),
                    'param_name' => 'max_width',
                    'description' => _x('Enter maximum width in pixels (e.g., 1200px)', 'visualcomposer'),
                    'value' => '1200px',
                    'dependency' => array(
                        'element' => 'use_container',
                        'is_empty' => true,
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => _x('Margin Top', 'visualcomposer'),
                    'param_name' => 'margin_top',
                    'value' => array(
                        'None' => 'none',
                        'Medium' => 'md',
                        'Large' => 'lg',
                        'Extra Large' => 'xl',
                    ),
                    'std' => 'md',
                    'description' => _x('Select top margin', 'visualcomposer'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => _x('Margin Bottom', 'visualcomposer'),
                    'param_name' => 'margin_bottom',
                    'value' => array(
                        'None' => 'none',
                        'Medium' => 'md',
                        'Large' => 'lg',
                        'Extra Large' => 'xl',
                    ),
                    'std' => 'md',
                    'description' => _x('Select bottom margin', 'visualcomposer'),
                ),
            ),
            'admin_enqueue_js' => get_template_directory_uri() . '/assets/backend.js',
            'js_view' => 'CustomElementView',
            'custom_markup' => $this->element_backend()
        ));
    }

    /**
     * Element backend.
     */
    public function element_backend()
    {
        return '<div class="module module-iframe">
            <h3>iFrame Block</h3>
            Platform: {{ params.platform }}<br />
            URL/ID: {{ params.video_url }}
        </div>';
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $atts = vc_map_get_attributes('vc_iframe_block', $atts);
        
        $atts = shortcode_atts(array(
            'platform' => 'youtube',
            'video_url' => '',
            'aspect_ratio' => '16:9',
            'width' => '100%',
            'max_width' => '1200px',
            'margin_top' => 'md',
            'margin_bottom' => 'md',
            'use_container' => ''
        ), $atts, 'vc_iframe_block');

        $platform = sanitize_text_field($atts['platform']);
        $video_url = sanitize_text_field($atts['video_url']);
        $aspect_ratio = sanitize_text_field($atts['aspect_ratio']);
        $width = sanitize_text_field($atts['width']);
        $max_width = sanitize_text_field($atts['max_width']);
        $margin_top = sanitize_text_field($atts['margin_top']);
        $margin_bottom = sanitize_text_field($atts['margin_bottom']);
        $use_container = $atts['use_container'] === 'yes';

        if (!$this->validate_url($video_url, $platform)) {
            return '<p>Invalid video URL format.</p>';
        }

        $embed_url = $this->get_embed_url($platform, $video_url);
        
        if (!$embed_url) {
            return '<p>Invalid video URL or unsupported platform.</p>';
        }

        $unique_id = 'iframe-' . uniqid();

        $margin_classes = array();
        if ($margin_top !== 'none') {
            $margin_classes[] = 'margin-top-' . $margin_top;
        }
        if ($margin_bottom !== 'none') {
            $margin_classes[] = 'margin-bottom-' . $margin_bottom;
        }

        $base_classes = ['iframe-block'];
        if ($use_container) {
            $base_classes[] = 'container';
        }
        $classes = array_merge($base_classes, $margin_classes);

        ob_start();
        ?>
        <div class="<?php echo esc_attr(implode(' ', $classes)); ?>" id="<?php echo esc_attr($unique_id); ?>" 
            style="width: <?php echo esc_attr($width); ?><?php echo !$use_container ? '; max-width: ' . esc_attr($max_width) : ''; ?>;">
            <div class="iframe-container aspect-ratio-<?php echo esc_attr(str_replace(':', '-', $aspect_ratio)); ?>">
                <iframe
                    src="<?php echo esc_url($embed_url); ?>"
                    frameborder="0"
                    scrolling="no"
                    allowfullscreen="true"
                    referrerpolicy="no-referrer-when-downgrade"
                    loading="lazy"
                    <?php if ($platform === 'youtube') : ?>
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    <?php elseif ($platform === 'twitch') : ?>
                    sandbox="allow-modals allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox allow-presentation"
                    allow="autoplay; fullscreen"
                    <?php endif; ?>
                ></iframe>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_embed_url($platform, $video_url)
    {
        switch ($platform) {
            case 'youtube':
                $video_id = $this->get_youtube_id($video_url);
                if (!$video_id) {
                    return false;
                }
                // Use YouTube's privacy-enhanced mode
                return "https://www.youtube-nocookie.com/embed/{$video_id}?rel=0&modestbranding=1";
                
            case 'twitch':
                $channel = $this->get_twitch_channel($video_url);
                if (!$channel) {
                    return false;
                }

                $parent = str_replace(['http://', 'https://'], '', $_SERVER['HTTP_HOST']);
                
                $params = array(
                    'parent' => $parent,
                    'autoplay' => 'false',
                    'muted' => 'false',
                    'allowfullscreen' => 'true',
                    // Add recommended Twitch parameters
                    'layout' => 'video',
                    'theme' => 'dark',
                    'time' => '0h0m0s'
                );
                
                if (is_numeric($channel)) {
                    $params['video'] = $channel;
                    return "https://player.twitch.tv/?" . http_build_query($params);
                } elseif (strpos($channel, 'video=') === 0) {
                    $params['video'] = substr($channel, 6);
                    return "https://player.twitch.tv/?" . http_build_query($params);
                } elseif (strpos($channel, 'clip=') === 0) {
                    $params['clip'] = substr($channel, 5);
                    return "https://clips.twitch.tv/embed?" . http_build_query($params);
                } else {
                    $params['channel'] = $channel;
                    return "https://player.twitch.tv/?" . http_build_query($params);
                }
                
            default:
                return false;
        }
    }

    private function get_youtube_id($url)
    {
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        $parsed_url = parse_url($url);
        
        if (isset($parsed_url['query']) && isset($parsed_url['host']) && strpos($parsed_url['host'], 'youtube.com') !== false) {
            parse_str($parsed_url['query'], $query_params);
            if (isset($query_params['v']) && strlen($query_params['v']) == 11) {
                return $query_params['v'];
            }
        }

        if (isset($parsed_url['host']) && $parsed_url['host'] === 'youtu.be') {
            $path = trim($parsed_url['path'], '/');
            if (strlen($path) === 11) {
                return $path;
            }
        }

        return false;
    }

    private function get_twitch_channel($url)
    {
        if (is_numeric($url)) {
            return $url;
        }

        $parsed_url = parse_url($url);
        
        if (isset($parsed_url['host']) && strpos($parsed_url['host'], 'twitch.tv') !== false) {
            $path = trim($parsed_url['path'], '/');
            
            if (preg_match('/^videos\/(\d+)/', $path, $matches)) {
                return 'video=' . $matches[1];
            } elseif (preg_match('/^(?:[^\/]+\/)?clip\/([a-zA-Z0-9_-]+)/', $path, $matches)) {
                return 'clip=' . $matches[1];
            } else {
                $segments = explode('/', $path);
                $channel = $segments[0];
                if (!empty($channel)) {
                    return $channel;
                }
            }
        }

        return false;
    }


    private function validate_url($url, $platform) {
        if (empty($url)) {
            return false;
        }

        $allowed_domains = [
            'youtube' => [
                'youtube.com',
                'youtube-nocookie.com',
                'youtu.be'
            ],
            'twitch' => [
                'twitch.tv',
                'player.twitch.tv',
                'clips.twitch.tv'
            ]
        ];

        // Allow direct IDs
        if ($platform === 'youtube' && preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return true;
        }
        if ($platform === 'twitch' && (is_numeric($url) || preg_match('/^[a-zA-Z0-9_]{4,25}$/', $url))) {
            return true;
        }

        $parsed_url = parse_url($url);
        if (!isset($parsed_url['host'])) {
            return false;
        }

        return in_array(str_replace('www.', '', $parsed_url['host']), $allowed_domains[$platform]);
    }
}

// Initialize the class
new ubisoft_iframe_block(); 