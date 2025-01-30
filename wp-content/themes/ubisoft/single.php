<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ubisoft
 */

get_header();

$featuredImage = get_post_thumbnail_id($post->ID);

$categories = get_the_category($post->ID);
$categoriesList = '';
$categoriesIds = array();
foreach ($categories as $category) {
    // echo '<li><a href="/category/' . $category->slug . '">' . $category->name . '</a></li>';
    $categoriesList .= '<li>' . $category->name . '</li>';
    $categoriesIds[] = $category->term_id;
}

?>
    <div class="single-post">
        <div class="wpb-content-wrapper">
            <div class="vc_row wpb_row vc_row-fluid">
                <div class="wpb_column vc_column_container vc_col-sm-1 vc_col-lg-2 vc_hidden-sm vc_hidden-xs"></div>
                <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-8 vc_col-md-10 vc_col-xs-12">
                    <div class="wpb_text_column wpb_content_element ">
                        <div class="single-post__header">
                            <?php if (!empty($categoriesList) && $post->post_type !== 'games') : ?>
                                <ul class="blog-post-categories">
                                    <?php echo $categoriesList; ?>
                                </ul>
                            <?php endif; ?>
                            <h1 class="h2"><?php echo get_the_title() ?></h1>
                            <div class="blog-post-meta">
                                <p>
                                    <?php
                                    if ($post->post_type !== 'games') {
                                        $formatter = new IntlDateFormatter(ICL_LANGUAGE_CODE, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                                        $dateTime = new DateTime($post->post_date);
                                        echo $formatter->format($dateTime);
                                    }
                                    ?>
                                </p>
                                <?php get_template_part('template-parts/sharing'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpb_column vc_column_container vc_col-sm-1 vc_col-lg-2 vc_hidden-sm vc_hidden-xs"></div>
            </div>
        </div>
        <?php if ($post->post_type === 'videos' && get_field('embed', $post->ID)): ?>
            <?php $embed = get_field('embed', $post->ID); ?>
            <div class="container">
                <div class="single-post__visual">
                    <?php if (shortcode_exists('borlabs-cookie')) : ?>
                        <?php if (strpos($embed, 'twitch.tv') !== false) : ?>
                            <?php echo do_shortcode('[borlabs-cookie id="twitch" type="content-blocker"]' . $embed . '[/borlabs-cookie]'); ?>
                        <?php else : ?>
                            <?php echo do_shortcode('[borlabs-cookie id="youtube" type="content-blocker"]' . $embed . '[/borlabs-cookie]'); ?>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php echo $embed; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif(!empty($featuredImage)) : ?>
            <div class="container">
                <div class="single-post__visual">
                    <?php echo wp_get_attachment_image($featuredImage, 'full'); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($post->post_type !== 'videos'): ?>
            <div class="wpb-content-wrapper">
                <div class="vc_row wpb_row vc_row-fluid">
                    <div class="wpb_column vc_column_container vc_col-sm-1 vc_col-lg-2 vc_hidden-sm vc_hidden-xs"></div>
                    <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-8 vc_col-md-10 vc_col-xs-12">
                        <div class="single-post__content">
                            <?php get_template_part('template-parts/content', 'page'); ?>
                        </div>
                    </div>
                    <div class="wpb_column vc_column_container vc_col-sm-1 vc_col-lg-2 vc_hidden-sm vc_hidden-xs"></div>
                </div>
            </div>
        <?php endif; ?>



        <!-- Voting -->
        <?php
        global $wpdb;

        $selected_poll = get_field('selected_poll', $post->ID);

        if ($selected_poll):
            $post_id = $selected_poll;
  
            // Use WPML to get the original poll ID
            $original_poll_id = apply_filters('wpml_object_id', $post_id, 'polls', true, 'en');
            if (!$original_poll_id) {
                $original_poll_id = $post_id; 
            }

            $logged_in = is_user_logged_in();
            $site_name = get_bloginfo('name');
            $poll_title = get_the_title($post_id);
            $feature_image = get_field('poll_feature_image', $post_id);
            $poll_items = get_field('poll_items', $post_id);
            $current_user_id = get_current_user_id();
            $current_date = date('Y-m-d');
            $has_voted = false;

            $original_poll_id = apply_filters('wpml_object_id', $post_id, 'polls', true, 'en');

            $settings_table_name = $wpdb->prefix . 'polls';
            $query = $wpdb->prepare(
                "SELECT poll_start_date, poll_end_date, poll_show_until_date, poll_type, poll_active
                FROM $settings_table_name WHERE original_post_id = %d",
                $original_poll_id
            );

            $poll_settings = $wpdb->get_row($query);

            $poll_start_date = $poll_settings->poll_start_date;
            $poll_end_date = $poll_settings->poll_end_date;
            $poll_show_until_date = $poll_settings->poll_show_until_date;
            $poll_type = $poll_settings->poll_type;
            $poll_active = $poll_settings->poll_active;

            $table_name = $wpdb->prefix . 'poll_votes';
            $existing_vote = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE poll_id = %d AND user_id = %d",
                $original_poll_id,
                $current_user_id
            ));


            if ($existing_vote) {
                $has_voted = true;
            }

            $total_votes = $wpdb->get_var($wpdb->prepare(
                "SELECT SUM(vote_count) FROM $table_name WHERE poll_id = %d",
                $original_poll_id
            )) ?: 0;

            $is_visible = ($current_date <= $poll_show_until_date);
            $is_active = ($current_date >= $poll_start_date && $current_date <= $poll_show_until_date && $poll_active);
            $can_vote = ($current_date >= $poll_start_date && $current_date <= $poll_end_date);

            if (!$can_vote || $has_voted) {
                $vote_btn_open_text = ICL_LANGUAGE_CODE == 'de' ? 'Ergebnisse anzeigen' : 'View Results';
            } else {
                $vote_btn_open_text = ICL_LANGUAGE_CODE == 'de' ? 'Jetzt abstimmen' : 'Vote Now';
            }

            $vote_btn_close_text = ICL_LANGUAGE_CODE == 'de' ? 'Schließen' : 'Close';



            

            if ($is_visible && $is_active): ?>

            <div class="blog-post-poll container">

                <div class="poll-card <?php echo !$can_vote ? 'voting-disabled' : ''; ?>" data-poll-id="<?php echo $post_id; ?>">
                    <?php if ($feature_image) : ?>
                        <div class="poll-image">
                            <?php echo wp_get_attachment_image($feature_image['ID'], 'small-landscape', '', array('alt' => $poll_title)); ?>
                        </div>
                    <?php endif; ?>

                    <div class="poll-content">

                        <h3><?php echo esc_html($poll_title); ?></h3>
                        <p class="poll-total-votes"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Gesamtstimmen:' : 'Total Votes:'; ?> <?php echo $total_votes; ?></p>
                
                        <p><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Frage:' : 'Question:'; ?>  <?php echo esc_html(get_field('poll_question', $post_id)); ?></p>

                        <div class="poll-options">
                            <?php foreach ($poll_items as $index => $item): ?>
                                <?php
                                // Fetch individual vote count from the custom table
                                $item_votes = $wpdb->get_var($wpdb->prepare(
                                    "SELECT vote_count FROM $table_name WHERE poll_id = %d AND item_index = %d",
                                    $original_poll_id,
                                    $index
                                )) ?: 0;

                                // Calculate percentage
                                $percentage = $total_votes ? round(($item_votes / $total_votes) * 100) : 0;
                                ?>

                                <div class="poll-option" data-item-index="<?php echo $index; ?>">
                                    <p><?php echo esc_html($item['choice_text']); ?></p>

                                    <div class="vote-statistics">
                                        <p class="vote-count"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Stimmen:' : 'Votes:'; ?> <?php echo $item_votes; ?></p>
                                        <p class="vote-percentage">
                                            <?php echo ICL_LANGUAGE_CODE == 'de' ? 'Prozentsatz:' : 'Percentage:'; ?> <?php echo $percentage; ?>%
                                        </p>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar" data-percentage="<?php echo $percentage; ?>"></div>
                                    </div>

                                    <?php if ($can_vote && !$has_voted && $logged_in): ?>
                                        <?php if ($poll_type === 'multiple'): ?>
                                            <label>
                                                <input type="checkbox" class="vote-checkbox" data-item-index="<?php echo $index; ?>" />
                                            </label>
                                        <?php else: ?>
                                            <button class="vote-btn"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Abstimmung' : 'Vote'; ?></button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>

                            <?php if ($poll_type === 'multiple' && $can_vote && !$has_voted && $logged_in): ?>
                                <button class="submit-multiple-btn"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Stimmen abgeben' : 'Submit Votes'; ?></button>
                            <?php endif; ?>
                        </div>

                        <?php if (!$can_vote): ?>
                            <p class="poll-closed-message"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Die Abstimmung für diese Umfrage ist jetzt geschlossen.' : 'Voting is now closed for this poll.'; ?></p>
                        <?php endif; ?>

                        <?php if ($has_voted): ?>
                            <p class="poll-closed-message"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Sie haben bereits an dieser Umfrage teilgenommen.' : 'You have already voted on this poll.'; ?></p>
                        <?php endif; ?>

                        <?php if (!$logged_in && $can_vote): ?>
                            <div class="login-btn-container">
                                <a class="login-vote-btn" href="/<?php echo ICL_LANGUAGE_CODE ?>/login" data-button="login">
                                    <?php echo ICL_LANGUAGE_CODE == 'de' ? 'Anmelden' : 'Login to vote'; ?>
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- End Voting -->


        <?php comments_template() ?>

        <?php

        $items = get_posts(array(
            'post_status' => 'publish',
            'post_type' => $post->post_type,
            'posts_per_page' => 10,
            'orderby' => 'date',
            'order' => 'DESC',
            'category' => implode(',', $categoriesIds), //(!empty($categories) ? $categories[0]->term_id : false),
            'exclude' => array($post->ID),
            'suppress_filters' => false,
        ));

        get_template_part('template-parts/posts-slider', null, array(
            'variant' => 'hero',
            'title_color' => 'color-purple',
            'background_color' => false,
            'background_image' => get_template_directory_uri() . '/assets/images/anno117-logo-bg.webp',
            'title' => __('Updates', 'ubisoft'),
            'button' => array(
                'url' => ICL_LANGUAGE_CODE == 'de' ? '/de/blog/' : '/blog/',
                'title' => ICL_LANGUAGE_CODE == 'de' ? 'Alle News' : 'View all News',
            ),
            'items' => $items,
        ));
        ?>

        <?php get_template_part('template-parts/social-channels'); ?>

    </div>

<?php
get_footer();
