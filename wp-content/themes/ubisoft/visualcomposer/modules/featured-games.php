<?php
/**
 * VC Module: Featured Games
 */

class ubisoft_featured_games extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_featured_games', array($this, 'element_frontend'));
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
        $gamesPosts = get_posts(array(
            'post_status' => 'publish',
            'post_type' => 'games',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'suppress_filters' => false,
        ));
        $games = array(
            '' => 'Please select'
        );
        foreach ($gamesPosts as $game) {
            $games[$game->post_title] = $game->ID;
        }

        // Map the block with vc_map()
        vc_map(
            array(
                'name' => _x('Featured Games', 'visualcomposer'),
                'description' => _x('Displays two featured games.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_featured_games',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'vc_link',
                        'heading' => _x('Primary Button', 'visualcomposer'),
                        'param_name' => 'primary_button',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Game #1', 'visualcomposer'),
                        'param_name' => 'game_1',
                        'value' => $games,
                        'std' => ''
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Game #2', 'visualcomposer'),
                        'param_name' => 'game_2',
                        'value' => $games,
                        'std' => ''
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Title Color', 'visualcomposer'),
                        'param_name' => 'title_color',
                        'value' => array(
                            'Purple' => 'purple',
                            'Scroll' => 'scroll',
                        ),
                        'std' => 'scroll'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Background Color', 'visualcomposer'),
                        'param_name' => 'background_color',
                        'value' => array(
                            'None' => '',
                            'Stone' => 'stone',
                            'Scroll' => 'scroll',
                        ),
                        'std' => ''
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Background Image', 'visualcomposer'),
                        'param_name' => 'background_image',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Background Gradient', 'visualcomposer'),
                        'param_name' => 'background_gradient',
                        'value' => array(
                            'None' => '',
                            'Dark Purple' => 'dark-purple',
                        ),
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
			<h3>Featured Games</h3>
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

    private function getItemHTML($item) {
        $teaserImage = get_field('post_teaser_image', $item);
        if (!empty($teaserImage)) {
            $image = wp_get_attachment_image($teaserImage['id'], 'full');
        } else {
            $featuredImage = get_post_thumbnail_id($item);
            $image = wp_get_attachment_image($featuredImage, 'full');
        }

        $title = get_the_title($item);
        if (str_starts_with(strtolower($title), 'anno ')) {
            $title = substr($title, strlen('anno '));
        }

        return '<div class="col-sm-6">
            <a href="' . get_permalink($item) . '" class="featured-games__item">
                <div class="featured-games__image">
                     ' . $image . '
                </div>
                <div class="featured-games__content">
                    <p class="featured-games__tags">ANNO</p>
                    <h3 class="h3">' . $title . '</h3>
                </div>
            </a>
        </div>';
    }

    private function getContentHTML($atts)
    {
        $title_color = !empty($atts['title_color']) ? 'color-' . $atts['title_color'] : 'color-scroll';
        $background_color = !empty($atts['background_color']) ? 'bg-' . $atts['background_color'] : '';
        $background_gradient = !empty($atts['background_gradient']) ? 'gradient-' . $atts['background_gradient'] : false;
        $background_image_src = !empty($atts['background_image']) ? wp_get_attachment_image_src($atts['background_image'], 'full') : '';
        $background_image = $background_image_src ? '<img src="' . $background_image_src[0] . '" alt="" class="background-image background-image--parallax" data-parallax-img />' : '';

        $buttons = '';
        if (!empty($atts['primary_button'])) {
            $link = vc_build_link($atts['primary_button']);
            $buttons .= '<a href="' . ($link['url'] ?? '') . '" class="button ' . ($title_color === 'color-purple' ? 'dark' : '') . '" ' . ($link['target'] !== '' ? 'target="' . $link['target'] . '"' : '') . ' ' . ($link['rel'] !== '' ? 'rel="' . $link['rel'] . '"' : '') . '>' . ($link['title'] ?? '') . '</a>';
        }

        $items = '';
        if (!empty($atts['game_1'])) {
            $items .= $this->getItemHTML($atts['game_1']);
        }

        if (!empty($atts['game_2'])) {
            $items .= $this->getItemHTML($atts['game_2']);
        }

        return '
            <div class="featured-games ' . $background_color . '">
                ' . $background_image . '
                ' . ($background_gradient ? '<div class="featured-games__gradient ' . $background_gradient . '"></div>' : '') . '
                <div class="container">
                    <div class="featured-games__header">
                        ' . (!empty($atts['title']) ? '<h2 class="h2 ' . $title_color . '">' . $atts['title'] . '</h2>' : '') . '
                        ' . $buttons . '
                    </div>
                    <div class="featured-games__games">
                        <div class="row">
                            ' . $items . '
                        </div>
                    </div>
                    <div class="featured-games__footer">
                        ' . $buttons . '
                    </div>
                </div>
            </div>';
    }
}

new ubisoft_featured_games();
