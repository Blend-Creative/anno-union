<?php
function handle_vote_ajax() {

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'vote_nonce')) {
        wp_send_json_error(['message' => 'Invalid request.']);
    }

    $poll_id = intval($_POST['poll_id']);
    $item_indices = isset($_POST['item_indices']) ? $_POST['item_indices'] : [intval($_POST['item_index'])];
    $user_id = get_current_user_id();
    $user_email = wp_get_current_user()->user_email;

    if (!$poll_id || empty($item_indices)) {
        wp_send_json_error(['message' => 'Invalid poll data.']);
    }

    $has_voted = false;
    if (have_rows('voted_users', $poll_id)) {
        while (have_rows('voted_users', $poll_id)) {
            the_row();
            if (get_sub_field('user_id') == $user_id) {
                $has_voted = true;
                break;
            }
        }
    }

    if ($has_voted) {
        wp_send_json_success(['can_vote' => false]);
    }

    add_row('voted_users', [
        'user_id' => $user_id,
        'user_email' => $user_email,
    ], $poll_id);

    $poll_items = get_field('poll_items', $poll_id);
    $updated_items = [];
    $total_votes = get_field('total_votes', $poll_id) ?: 0;

    foreach ($item_indices as $index) {
        $poll_items[$index]['vote_count']++;
        $total_votes++;
        $updated_items[] = [
            'index' => $index,
            'vote_count' => $poll_items[$index]['vote_count'],
            'percentage' => round(($poll_items[$index]['vote_count'] / $total_votes) * 100),
        ];
    }

    update_field('poll_items', $poll_items, $poll_id);
    update_field('total_votes', $total_votes, $poll_id);

    wp_send_json_success([
        'poll_count' => $total_votes,
        'updated_items' => $updated_items,
    ]);
}

add_action('wp_ajax_cast_vote', 'handle_vote_ajax');






if (have_posts()) : ?>
    <div class="poll-archive-container container">
        <?php while (have_posts()) : the_post();
            $logged_in = is_user_logged_in();
            $site_name = get_bloginfo('name');
            $post_id = get_the_ID();
            $poll_active = get_field('poll_active');
            $poll_type = get_field('poll_type');
            $poll_title = get_the_title();
            $feature_image = get_field('poll_feature_image');
            $total_votes = get_field('total_votes') ?: 0;
            $poll_items = get_field('poll_items');
            $voted_users = get_field('voted_users') ?: [];
            $current_user_id = get_current_user_id();
            $has_voted = false;

            $start_date = get_field('poll_start_date');
            $end_date = get_field('poll_end_date');
            $show_until_date = get_field('poll_show_until_date');
            $current_date = date('Y-m-d');

            $is_visible = ($current_date <= $show_until_date);
            $is_active = ($current_date >= $start_date && $current_date <= $show_until_date && $poll_active);
            $can_vote = ($current_date >= $start_date && $current_date <= $end_date);

            if (!empty($voted_users)) {
                foreach ($voted_users as $voter) {
                    if ($voter['user_id'] == $current_user_id) {
                        $has_voted = true;
                        break;
                    }
                }
            }

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
                        <div class="poll-option" data-item-index="<?php echo $index; ?>">
                            <p><?php echo esc_html($item['choice_text']); ?></p>

                            <div class="vote-statistics">
                                <p class="vote-count"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Stimmen:' : 'Votes:'; ?> <?php echo $item['vote_count']; ?></p>
                                <p class="vote-percentage">
                                    <?php echo ICL_LANGUAGE_CODE == 'de' ? 'Prozentsatz:' : 'Percentage:'; ?>
                                    <?php 
                                    $percentage = $total_votes ? round(($item['vote_count'] / $total_votes) * 100) : 0; 
                                    echo $percentage; 
                                    ?>%
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
        <?php endif; ?>

        <?php endwhile; ?>
    </div>

<?php
else:
    echo '<p>No polls available.</p>';
endif;







            $selected_poll = get_field('selected_poll', $post->ID); 

            if ($selected_poll):
                $logged_in = is_user_logged_in();
                $post_id = $selected_poll;
                $poll_active = get_field('poll_active', $post_id);
                $poll_type = get_field('poll_type', $post_id);
                $poll_title = get_the_title($post_id);
                $feature_image = get_field('poll_feature_image', $post_id);
                $total_votes = get_field('total_votes', $post_id) ?: 0;
                $poll_items = get_field('poll_items', $post_id);
                $voted_users = get_field('voted_users', $post_id) ?: [];
                $current_user_id = get_current_user_id();
                $has_voted = false;

                $start_date = get_field('poll_start_date', $post_id);
                $end_date = get_field('poll_end_date', $post_id);
                $show_until_date = '9999-01-01';
                $current_date = date('Y-m-d');

                $is_visible = ($current_date <= $show_until_date);
                $is_active = ($current_date >= $start_date && $current_date <= $show_until_date && $poll_active);
                $can_vote = ($current_date >= $start_date && $current_date <= $end_date);

                if (!empty($voted_users)) {
                    foreach ($voted_users as $voter) {
                        if ($voter['user_id'] == $current_user_id) {
                            $has_voted = true;
                            break;
                        }
                    }
                }

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
                
                        <p><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Frage:' : 'Question:'; ?>  <?php echo esc_html(get_field('poll_question')); ?></p>

                        <div class="poll-options">
                            <?php foreach ($poll_items as $index => $item): ?>
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