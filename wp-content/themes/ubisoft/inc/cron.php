<?php
// =================================================================================
// Poll Cron update
// =================================================================================

function schedule_poll_status_cron() {
    if (!wp_next_scheduled('update_poll_status_event')) {
        wp_schedule_event(time(), 'hourly', 'update_poll_status_event'); // Runs hourly
    }
}
add_action('wp', 'schedule_poll_status_cron');


function update_poll_status() {
    $args = [
        'post_type' => 'polls',
        'posts_per_page' => -1,
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'poll_start_date',
                'compare' => 'EXISTS',
            ],
            [
                'key' => 'poll_active',
                'compare' => 'EXISTS',
            ],
        ],
    ];

    $polls = get_posts($args);
    $current_date = date('Y-m-d');

    foreach ($polls as $poll) {
        $post_id = $poll->ID;
        $start_date = get_field('poll_start_date', $post_id);
        $end_date = get_field('poll_show_until_date', $post_id);
        $poll_active = get_field('poll_active', $post_id);

        if ($current_date >= $start_date && (!$end_date || $current_date <= $end_date)) {
            if (!$poll_active) {
                update_field('poll_active', true, $post_id);
            }
        } else {
            if ($poll_active) {
                update_field('poll_active', false, $post_id);
            }
        }
    }
}
add_action('update_poll_status_event', 'update_poll_status');