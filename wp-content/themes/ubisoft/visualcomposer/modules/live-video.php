<?php
/**
 * VC Module: Live Video
 */

class ubisoft_live_video extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_live_video', array($this, 'element_frontend'));
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
                'name' => _x('Live Video', 'visualcomposer'),
                'description' => _x('Show a live video object (if a video is currently live)', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_live_video',
                'params' => [],
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
			<h3>Live Video</h3>
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        // Check if any video is currently live.
        $videos = get_posts([
            'post_type' => 'videos',
            'posts_per_page' => -1,
            'suppress_filters' => false
        ]);

        $liveVideos = [];   // array as perhaps there's more than one at once.

        foreach ($videos as $index => $video) {
            if (get_field('video_is_live', $video->ID)) {

                $categories = '';

                foreach (get_the_category($video->ID) as $index => $category) {
                    $categories .= '<li><a href="/category/' . $category->slug . '">' . $category->name . '</a></li>';
                }

                $liveVideos[] = [
                    'title' => $video->post_title,
                    'excerpt' => $video->post_excerpt,
                    'date' => $video->post_date,
                    'categories' => $categories,
                    'embed' => get_field('embed', $video->ID),
                    'author' => get_the_author_meta('display_name', $video->author)
                ];
            }
        }

        if (count($liveVideos) == 0) {
            return;
        }

        $html = '';

        foreach ($liveVideos as $index => $video) {
            $html .= '
                <section class="live-video">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8" data-video-embed>
                                ' . $video['embed'] . '
                            </div>
                            <div class="col-md-4" data-video-meta>
                                <img src="' . get_template_directory_uri() . '/images/icon-live-black.svg" width="40" height="40" />
                                <h4 class="h4">' . $video['title'] . '</h4>
                                <p>' . $video['excerpt'] . '</p>
                                <ul class="blog-post-categories">
                                    <li>' . date('d.m.Y', strtotime($video['date'])) . '</li>
                                    ' . $categories . '
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>';
        }

        return $html;
    }
}

new ubisoft_live_video();
