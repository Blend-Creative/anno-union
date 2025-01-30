<?php
/**
 * VC Module: Headline
 */

class ubisoft_media_teaser extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_media_teaser', array($this, 'element_frontend'));
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
                'name' => 'Media Teaser',
                'description' => 'Creates a "Media Teaser" section.',
                'category' => 'Content',
                'base' => 'ubisoft_media_teaser',
                'params' => [
                    [
                        'type' => 'dropdown',
                        'heading' => 'Media Type',
                        'param_name' => 'media_type',
                        'value' => [
                            'Image' => 'image',
                            'Video' => 'video'
                        ],
                        'std' => 'image'
                    ], [
                        'type' => 'attach_image',
                        'heading' => _x('Image', 'visualcomposer'),
                        'param_name' => 'image_id',
                        'dependency' => [
                            [
                                'element' => 'media_type',
                                'value' => ['image']
                            ]
                        ]
                    ], [
                        'type' => 'file_picker',
                        'heading' => 'Video',
                        'param_name' => 'video_id',
                        'value' => '',
                        'description' => 'Enter Video (MP4)',
                        'dependency' => [
                            [
                                'element' => 'media_type',
                                'value' => ['video']
                            ]
                        ]
                    ],
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
                ],
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
			<h3>Media Teaser</h3>
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $image = empty($atts['image_id']) ? '' : wp_get_attachment_image_url($atts['image_id'], 'xxl');
        $video = empty($atts['video_id']) ? '' : wp_get_attachment_url($atts['video_id']);

        if (isset($atts['media_type']) && 'video' === $atts['media_type']) {
            $content = '<div class="media-teaser-video" data-video="' . $video . '" data-poster="' . $image . '"><div></div></div>';
        } else {
            $content = '<div class="media-teaser-image" style="background-image: url(' . $image . ');"></div>';
        }

        $wrapper_class = make_section_classes($atts, 'media-teaser-full-width');

        return '<section class="' . $wrapper_class . '">' . $content . '</section>';
    }
}

new ubisoft_media_teaser();
