<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ubisoft
 */

get_header();
?>

<?php
global $wpdb;

if (have_posts()) : ?>
    <div class="poll-archive-container container">
        <?php while (have_posts()) : the_post();

            $post_id = get_the_ID();

            // Use WPML to get the original poll ID
            $original_poll_id = apply_filters('wpml_object_id', $post_id, 'polls', true, 'en');
            if (!$original_poll_id) {
                $original_poll_id = $post_id; 
            }

            $logged_in = is_user_logged_in();
            $site_name = get_bloginfo('name');
            $poll_title = get_the_title();
            $feature_image = get_field('poll_feature_image');
            $poll_items = get_field('poll_items');
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

        ?>

        <?php if ($is_visible && $is_active): ?>
            <div class="poll-card <?php echo !$can_vote ? 'voting-disabled' : ''; ?>" data-poll-id="<?php echo $post_id; ?>">

                <?php if ($feature_image) : ?>
                    <div class="poll-image">
                        <?php echo wp_get_attachment_image($feature_image['ID'], 'small-landscape', '', array('alt' => $site_name . ' - ' . $poll_title)); ?>
                    </div>
                <?php endif; ?>

                <h3><?php echo esc_html($poll_title); ?></h3>
                <p class="poll-total-votes"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Gesamtstimmen:' : 'Total Votes:'; ?> <?php echo $total_votes; ?></p>
                
                <p><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Frage:' : 'Question:'; ?>  <?php echo esc_html(get_field('poll_question')); ?></p>

                <div class="toggle-btn-container">
                    <button class="toggle-options-btn" data-open="<?php echo esc_html($vote_btn_open_text); ?>" data-close="<?php echo esc_html($vote_btn_close_text); ?>"><?php echo esc_html($vote_btn_open_text); ?></button>
                </div>

                <div class="poll-options">
                    <?php foreach ($poll_items as $index => $item): ?>
                        <?php
                        $item_votes = $wpdb->get_var($wpdb->prepare(
                            "SELECT vote_count FROM $table_name WHERE poll_id = %d AND item_index = %d",
                            $original_poll_id,
                            $index
                        )) ?: 0;
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

                <?php if ($has_voted && $can_vote): ?>
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
        <?php endif; ?>

        <?php endwhile; ?>
    </div>

<?php else: ?>


    <div class="container">
        <h2 class="poll-none-message"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Es sind leider keine Pole verfügbar' : 'Sorry, there are no polls available'; ?></h2>
    </div>

<?php
endif;


get_footer();

