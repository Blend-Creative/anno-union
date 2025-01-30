<?php
/**
 * VC Module: Headline
 */

class ubisoft_features_teaser extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_features_teaser', array($this, 'element_frontend'));
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
                'name' => _x('Features Teaser', 'visualcomposer'),
                'description' => _x('A list of image teasers in three columns.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_features_teaser',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'param_group',
                        'value' => '',
                        'param_name' => 'teasers',
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
                                'type' => 'attach_image',
                                'heading' => _x('Image', 'visualcomposer'),
                                'param_name' => 'image',
                            ),
                            array(
                                'type' => 'dropdown',
                                'heading' => _x('Video', 'visualcomposer'),
                                'param_name' => 'video',
                                'value' => $videos,
                                'std' => ''
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
			<h3>Features Teaser</h3>
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
        $items = '';
        $teasers = empty($atts['teasers']) ? [] : vc_param_group_parse_atts($atts['teasers']);
        foreach ($teasers as $teaser) {
            $image = !empty($teaser['image']) ? wp_get_attachment_image($teaser['image'], 'small-landscape') : '';
            $video = !empty($teaser['video']) ? $teaser['video'] : false;
            $modal = '';

            $buttons = '';
            if (!empty($teaser['primary_button'])) {
                $link = vc_build_link($teaser['primary_button']);
                $buttons .= '<a href="' . ($link['url'] ?? '') . '" class="button dark" ' . ($link['target'] !== '' ? 'target="' . $link['target'] . '"' : '') . ' ' . ($link['rel'] !== '' ? 'rel="' . $link['rel'] . '"' : '') . '>' . ($link['title'] ?? '') . '</a>';
                if (!empty($image)) {
                    $image = '<a href="' . ($link['url'] ?? '') . '" ' . ($link['target'] !== '' ? 'target="' . $link['target'] . '"' : '') . ' ' . ($link['rel'] !== '' ? 'rel="' . $link['rel'] . '"' : '') . '>' . $image . '</a>';
                }
            }

            if ($video) {
                $modalId = 'modal-' . wp_rand();
                $embed = get_field('embed', $video);
                if (!empty($embed)) {
                    if (!empty($image)) {
                        $image = '<a href="' . get_permalink($video) . '" data-toggle="modal" data-target="#' . $modalId . '" data-video-modal-button>' . $image . '</a>';
                        $image .= '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                          <rect width="48" height="48" fill="none" stroke-width="0"/>
                          <path d="M24,.4C11,.4.4,11,.4,24s10.6,23.6,23.6,23.6,23.6-10.6,23.6-23.6S37,.4,24,.4ZM24,43.6c-10.8,0-19.6-8.8-19.6-19.6S13.2,4.4,24,4.4s19.6,8.8,19.6,19.6-8.8,19.6-19.6,19.6Z" fill="currentColor" stroke-width="0"/>
                          <path d="M19.9,32.1c.3.1.6.2.9.2s.7,0,1-.3l9.7-6.6c.5-.3.8-.9.8-1.5s-.3-1.2-.8-1.5l-9.7-6.4c-.6-.4-1.2-.4-1.9,0-.6.3-1,.9-1,1.6v13.1c0,.7.4,1.3,1,1.6h0Z" fill="currentColor" stroke-width="0"/>
                        </svg>';
                    }
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

            $items .= '<div class="col-12 col-sm-6 col-lg-4"><div class="features-teaser__item">
                <div class="features-teaser__image">
                    ' . $image . '
                </div>
                ' . (!empty($teaser['title']) ? '<h3 class="h4">' . $teaser['title'] . '</h3>' : '') . '
                ' . (!empty($teaser['copy']) ? '<p>' . $teaser['copy'] . '</p>' : '') . '
                ' . $buttons . '
            </div>' . $modal . '</div>';
        }

        return '
            <div class="features-teaser bg-purple">
                <div class="container">
                    <div class="features-teaser__header">
                        ' . (!empty($atts['title']) ? '<h2 class="h2 color-scroll">' . $atts['title'] . '</h2>' : '') . '
                    </div>
                    <div class="row">
                        ' . $items . '
                    </div>
                </div>
            </div>';
    }
}

new ubisoft_features_teaser();
