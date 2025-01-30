<?php
/**
 * VC Module: Video Stage
 */

class ubisoft_video_stage extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_video_stage', array($this, 'element_frontend'));
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

        // Get dropdown values
        $videosPosts = get_posts(array(
            'post_status' => 'publish',
            'post_type' => 'videos',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'suppress_filters' => false,
        ));
        $videos = array(
            '' => 'Please select'
        );
        foreach ($videosPosts as $video) {
            $videos[$video->post_title] = $video->ID;
        }

        // Map the block with vc_map()
        vc_map(
            array(
                'name' => _x('Video Stage', 'visualcomposer'),
                'description' => _x('Stage module with a video modal.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_video_stage',
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Background Image', 'visualcomposer'),
                        'param_name' => 'background_image',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Headline Image', 'visualcomposer'),
                        'param_name' => 'headline_image',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Button Image', 'visualcomposer'),
                        'param_name' => 'button_image',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Video', 'visualcomposer'),
                        'param_name' => 'video',
                        'value' => $videos,
                        'std' => ''
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
			<h3>Video Stage</h3>
			{{ params.title }}
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
        $background_image_src = !empty($atts['background_image']) ? wp_get_attachment_image_src($atts['background_image'], 'full') : '';
        $headline_image_src = !empty($atts['headline_image']) ? wp_get_attachment_image_src($atts['headline_image'], 'xxl') : '';
        $button_image_src = !empty($atts['button_image']) ? wp_get_attachment_image_src($atts['button_image'], 'full') : '';

        $background_image = $background_image_src ? '<img src="' . $background_image_src[0] . '" alt="" width="' . $background_image_src[1] . '" height="' . $background_image_src[2] . '" class="video-stage__background">' : '';
        $headline_image = $headline_image_src ? '<img src="' . $headline_image_src[0] . '" alt="' . get_post_meta($atts['headline_image'], '_wp_attachment_image_alt', true) . '" class="video-stage__headline">' : '';
        $button_image = $button_image_src ? '<img src="' . $button_image_src[0] . '" alt="' . get_post_meta($atts['button_image'], '_wp_attachment_image_alt', true) . '" class="video-stage__button">' : '';

        $video = !empty($atts['video']) ? $atts['video'] : false;
        $button = '';
        $modal = '';
        if ($video) {
            $modalId = 'modal-' . wp_rand();
            $embed = get_field('embed', $video);
            if (!empty($embed) && !empty($button_image)) {
                $button = '<a href="' . get_permalink($video) . '" data-toggle="modal" data-target="#' . $modalId . '" data-video-modal-button>' . $button_image . '</a>';

                $modal = '<div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-hidden="true" data-video-modal>
                  <div class="modal-dialog modal-dialog-centered modal-xl modal-video">
                    <div class="modal-content">
                      <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="currentColor" d="M18.7,6.7l-1.4-1.4L12,10.6L6.7,5.3L5.3,6.7l5.3,5.3l-5.3,5.3l1.4,1.4l5.3-5.3l5.3,5.3l1.4-1.4L13.4,12L18.7,6.7z"/></svg>
                        </button>
                        <div class="embed-responsive embed-responsive-16by9">
                            ' . str_replace('src=', 'data-src=', $embed) . '
                        </div>
                      </div>
                    </div>
                  </div>
                </div>';
            }
        }

        return '
            <div class="video-stage" data-video-stage>
                ' . $background_image . '
                <div class="video-stage__content" style="background-image: url(' . $background_image_src[0] . ');">
                    ' . $headline_image . '
                    ' . $button . '
                </div>
                ' . $modal . '
            </div>';
    }
}

new ubisoft_video_stage();
