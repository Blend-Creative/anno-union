<?php
/**
 * VC Module: Post Filter
 */

class ubisoft_post_filter extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_post_filter', array($this, 'element_frontend'));
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
                'name' => _x('Post Filter', 'visualcomposer'),
                'description' => _x('Show a filterable list of posts', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_post_filter',
                'params' => [
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Include blog posts?', 'visualcomposer'),
                        'param_name' => 'include_blog_posts',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Include game posts?', 'visualcomposer'),
                        'param_name' => 'include_game_posts',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Include video posts?', 'visualcomposer'),
                        'param_name' => 'include_video_posts',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Include update posts?', 'visualcomposer'),
                        'param_name' => 'include_update_posts',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Include events posts?', 'visualcomposer'),
                        'param_name' => 'include_event_posts',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Include voting posts?', 'visualcomposer'),
                        'param_name' => 'include_voting_posts',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Include categories posts?', 'visualcomposer'),
                        'param_name' => 'include_category_posts',
                    ),
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
			<h3>Post Filter</h3>
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $includedTypes = [];
        $filterTypes = [];

        // [ 'post', 'games', 'videos', 'updates', 'events', 'voting', 'newscategories' ]
        if (!empty($atts['include_blog_posts'])) {
            $includedTypes[] = 'post';
            $filterTypes[] = [
                'label' => __('Blog Posts', 'ubisoft'),
                'value' => 'post'
            ];
        }
        if (!empty($atts['include_game_posts'])) {
            $includedTypes[] = 'games';
            $filterTypes[] = [
                'label' => __('Games', 'ubisoft'),
                'value' => 'games'
            ];
        }
        if (!empty($atts['include_video_posts'])) {
            $includedTypes[] = 'videos';
            $filterTypes[] = [
                'label' => __('Videos', 'ubisoft'),
                'value' => 'videos'
            ];
        }
        if (!empty($atts['include_update_posts'])) {
            $includedTypes[] = 'updates';
            $filterTypes[] = [
                'label' => __('Updates', 'ubisoft'),
                'value' => 'updates'
            ];
        }
        if (!empty($atts['include_event_posts'])) {
            $includedTypes[] = 'events';
            $filterTypes[] = [
                'label' => __('Events', 'ubisoft'),
                'value' => 'events'
            ];
        }
        if (!empty($atts['include_voting_posts'])) {
            $includedTypes[] = 'voting';
            $filterTypes[] = [
                'label' => __('Voting', 'ubisoft'),
                'value' => 'voting'
            ];
        }
        if (!empty($atts['include_category_posts'])) {
            $includedTypes[] = 'newscategories';
            $filterTypes[] = [
                'label' => __('Categories', 'ubisoft'),
                'value' => 'newscategories'
            ];
        }

        $latest_posts = get_posts([
            'post_type' => $includedTypes,
            'posts_per_page' => -1,
            'suppress_filters' => false
        ]);

        // Build <select> options
        $filterTypesOptions = '';
        foreach($filterTypes as $index => $type) {
            $filterTypesOptions .= '<option data-translate value="' . $type['value'] . '">' . $type['label'] . '</option>';
        }

        if (empty($latest_posts)) {
            return '';
        }

        $teaser_html = [];

        $allTags = [];

        foreach ($latest_posts as $latest_post) {

            $tags = get_the_tags($latest_post->ID);
            $tagList = '';

            if (is_array($tags)) {
                foreach ($tags as $index => $tag) {
                    $allTags[$tag->name] = $tag->slug;
                    if ($tagList == '') {
                        $tagList = $tag->slug;
                    } else {
                        $tagList .= ',' . $tag->slug;
                    }
                }
            }

            $categories = get_the_category($latest_post->ID);
            $category = '<em>';
            foreach ($categories as $index => $_category) {
                if($index > 1) continue;
                if ($index > 0) {
                    $category .= ', ' . $_category->cat_name;
                } else {
                    $category .= $_category->cat_name;
                }
            }
            $category .= '</em>';

            $postType = $latest_post->post_type;

            $date = date('d.m.Y', strtotime($latest_post->post_date));

            $excerpt = '<p>' . filter_var(str_replace('"', '&#8220;', $latest_post->post_excerpt), FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '</p>';

            $image = get_the_post_thumbnail( $latest_post->ID, 'large', array( 'class' => 'alignleft' ) );

            if ($postType == 'updates') {
                if(get_the_post_thumbnail( $latest_post->ID )){
                    $image = get_the_post_thumbnail( $latest_post->ID, 'large', array( 'class' => 'alignleft' ) );

                }else{
                   $image = '<img src="'.get_template_directory_uri() . '/assets/images/updates-header-placeholder.jpg'.'" alt="Updates" title="'.filter_var(str_replace('"', '&#8220;', $latest_post->post_title), FILTER_SANITIZE_FULL_SPECIAL_CHARS)
                   .'" class="alignleft wp-post-image" />';

                }
            }
            $specialDate = null;

            if ($postType == 'events' || $postType == 'videos') {
                if ($startDate = get_field('date_from', $latest_post->ID)) {
                    $specialDate = '<span data-event-date>' . $startDate . '</span>';
                }
            }

            $meta = '<div class="meta">
                <span>' . $date . '</span>
                <span>' . $category . '</span>
            </div>';

            $postTypeDisplay = '<span>' . ucwords($postType) . '</span>';

            if ($postType == 'newscategories') {
                $meta = null;
                $postTypeDisplay = null;
            }



            if($postType == 'videos') {
                $embed = get_field('embed', $latest_post->ID);
                $embed_str ='';
                if($embed){
                    if (shortcode_exists('borlabs-cookie')) {
						if (strpos($embed, 'twitch.tv') !== false) {
							$embed = htmlspecialchars(htmlentities(do_shortcode('[borlabs-cookie id="twitch" type="content-blocker"]' . $embed . '[/borlabs-cookie]')));
						} else {
							$embed = htmlspecialchars(htmlentities(do_shortcode('[borlabs-cookie id="youtube" type="content-blocker"]' . $embed . '[/borlabs-cookie]')));
						}
					} else {
						$embed = htmlentities(str_replace("https:","",$embed));
						$embed = htmlentities(str_replace('class="lazyload"',"",$embed));
					}
					$embed_str= 'data-embed=\'' . json_encode($embed,JSON_UNESCAPED_SLASHES) . '\'';
                }

                $markup = '<div>

                    <div>
                        ' . $postTypeDisplay . ' <a href="' . get_the_permalink($latest_post->ID) . '">
                        <strong>' . filter_var(str_replace('"', '&#8220;', $latest_post->post_title), FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '</strong>

                ' . $excerpt . '</a>
                    </div>
                </div>';

                $teaser_html[] = '<span '.$embed_str.' data-markup=\'' . json_encode($markup) . '\' data-tags=\'' . $tagList . '\' data-type=\'' . $postType . '\' data-year=\'' . date('Y', strtotime($latest_post->post_date)) . '\' data-month=\'' . date('m', strtotime($latest_post->post_date)) . '\' data-day=\'' . date('d', strtotime($latest_post->post_date)) . '\' data-id=\'' . $latest_post->ID . '\'></span>';


            }else{
                $markup = '<a href="' . get_the_permalink($latest_post->ID) . '">
                ' . $image . '
                ' . $specialDate . '
                <div>
                    ' . $postTypeDisplay . '
                    <strong>' . filter_var(str_replace('"', '&#8220;', $latest_post->post_title), FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '</strong>
                    ' . $excerpt . '
                    ' . $meta . '
                </div>
            </a>';

            $teaser_html[] = '<span data-markup=\'' . json_encode($markup) . '\' data-tags="' . $tagList . '" data-type="' . $postType . '" data-year="' . date('Y', strtotime($latest_post->post_date)) . '" data-month="' . date('m', strtotime($latest_post->post_date)) . '" data-day="' . date('d', strtotime($latest_post->post_date)) . '" data-id="' . $latest_post->ID . '"></span>';
            }


        }

        $tags = '';
        foreach ($allTags as $index => $tag) {
            $tags .= '<li class="button dark outline" data-tag="' . $tag . '">' . $index . '</li>';
        }

        $wrapper_class = make_section_classes($atts, 'section-post-filter-container');

        $typesDropdown = null;

        if (count($filterTypes) > 1) {
            $typesDropdown = '<div class="select-wrapper">
                <select name="filter-type">
                    <option value="">All Types</option>
                    ' . $filterTypesOptions . '
                </select>
            </div>';
        }

        $datePicker = '';
        // $datePicker = '<div class="date-wrapper">
        //     <input type="date" name="filter-date" value="" />
        // </div>';

        $html = '
            <section class="' . $wrapper_class . '" data-post-filter data-types="Game,Video Previews,Updates,Press Releases">
              <div class="container">
                <div class="row section-post-filter">
                    <form>
                        ' . $typesDropdown . '
                        ' . $datePicker . '

                        <ul class="filter-tags">
                            ' . $tags . '
                        </ul>
                    </form>

                    ' . implode('', $teaser_html) . '
                </div>

                <div class="row section-press-cta-row">
                  <div class="col-md-12">
                    <div class="text-center">
                      <a href="#" style="display: none;" class="button dark" data-filter-load-more><span>' . __('Load more', 'ubisoft') . '</span></a>
                    </div>
                  </div>
                </div>
              </div>
            </section>';

        return $html;
    }
}

new ubisoft_post_filter();
