<?php

// Init to create Poll Votes table
function create_poll_votes_table() {
    global $wpdb;


    $polls_table = $wpdb->prefix . 'polls';

    if ($wpdb->get_var("SHOW TABLES LIKE '$polls_table'") != $polls_table) {
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $polls_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            original_post_id BIGINT(20) UNSIGNED NOT NULL,
            poll_start_date DATE NOT NULL,
            poll_end_date DATE NOT NULL,
            poll_show_until_date DATE NOT NULL,
            poll_type VARCHAR(20) NOT NULL,
            poll_active BOOLEAN DEFAULT 1,
            PRIMARY KEY (id),
            UNIQUE KEY original_post_id (original_post_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        if ($wpdb->last_error) {
            error_log("Database error creating table $polls_table: " . $wpdb->last_error);
        } else {
            error_log("Table $polls_table created successfully.");
        }
    }


    $poll_votes_table = $wpdb->prefix . 'poll_votes';

    if ($wpdb->get_var("SHOW TABLES LIKE '$poll_votes_table'") != $poll_votes_table) {
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $poll_votes_table (
            id BIGINT(20) NOT NULL AUTO_INCREMENT,
            poll_id BIGINT(20) NOT NULL,
            item_index INT NOT NULL,
            user_id BIGINT(20) DEFAULT NULL,
            vote_count INT DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY poll_user_item (poll_id, user_id, item_index)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        if ($wpdb->last_error) {
            error_log("Database error creating table $poll_votes_table: " . $wpdb->last_error);
        } else {
            error_log("Table $poll_votes_table created successfully.");
        }
    }





}

add_action('init', 'create_poll_votes_table');



// =================================================================================
// Poll Voting
// =================================================================================
function handle_vote_ajax() {
    global $wpdb;

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

    $original_poll_id = apply_filters('wpml_object_id', $poll_id, 'polls', true, 'en');

    $table_name = $wpdb->prefix . 'poll_votes';
    $existing_vote = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE poll_id = %d AND user_id = %d",
        $original_poll_id,
        $user_id
    ));

    if ($existing_vote) {
        wp_send_json_error(['message' => 'You have already voted on this poll.']);
    }

    $updated_items = [];
    foreach ($item_indices as $index) {
        $wpdb->query($wpdb->prepare(
            "INSERT INTO $table_name (poll_id, item_index, user_id, vote_count)
            VALUES (%d, %d, %d, 1)
            ON DUPLICATE KEY UPDATE vote_count = vote_count + 1",
            $original_poll_id,
            $index,
            $user_id
        ));

        $item_votes = $wpdb->get_var($wpdb->prepare(
            "SELECT vote_count FROM $table_name WHERE poll_id = %d AND item_index = %d",
            $original_poll_id,
            $index
        ));

        $updated_items[] = [
            'index' => $index,
            'vote_count' => $item_votes,
        ];
    }

    $total_votes = $wpdb->get_var($wpdb->prepare(
        "SELECT SUM(vote_count) FROM $table_name WHERE poll_id = %d",
        $original_poll_id
    ));

    foreach ($updated_items as &$item) {
        $item['percentage'] = round(($item['vote_count'] / $total_votes) * 100);
    }

    wp_send_json_success([
        'poll_count' => $total_votes,
        'updated_items' => $updated_items,
    ]);
}

add_action('wp_ajax_cast_vote', 'handle_vote_ajax');




// Sync poll data in admin view
function sync_poll_data_to_acf($post_id) {
    global $wpdb;

    if (get_post_type($post_id) !== 'polls') {
        return;
    }

    $original_poll_id = apply_filters('wpml_object_id', $post_id, 'polls', true, 'en');

    $total_votes = $wpdb->get_var($wpdb->prepare(
        "SELECT SUM(vote_count) FROM {$wpdb->prefix}poll_votes WHERE poll_id = %d",
        $original_poll_id
    )) ?: 0;

    $poll_items = get_field('poll_items', $post_id);
    if ($poll_items) {
        foreach ($poll_items as $index => $item) {
            $item_votes = $wpdb->get_var($wpdb->prepare(
                "SELECT vote_count FROM {$wpdb->prefix}poll_votes WHERE poll_id = %d AND item_index = %d",
                $original_poll_id,
                $index
            )) ?: 0;

            $poll_items[$index]['vote_count'] = $item_votes;
        }

        update_field('poll_items', $poll_items, $post_id);
    }

    $voted_users = $wpdb->get_results($wpdb->prepare(
        "SELECT DISTINCT user_id FROM {$wpdb->prefix}poll_votes WHERE poll_id = %d",
        $original_poll_id
    ));

    $acf_voted_users = [];
    foreach ($voted_users as $user) {
        $acf_voted_users[] = [
            'user_id' => $user->user_id,
            'user_email' => get_userdata($user->user_id)->user_email,
        ];
    }

    update_field('voted_users', $acf_voted_users, $post_id);
    update_field('total_votes', $total_votes, $post_id);
}



add_action('current_screen', function ($screen) {
    if ($screen->post_type === 'polls' && $screen->base === 'post') {
        $post_id = $_GET['post'] ?? 0;
        if ($post_id) {
            sync_poll_data_to_acf($post_id);
        }
    }
});



// Sync poll settings on post save regardless of translation
function sync_poll_settings_to_db($post_id) {
    global $wpdb;

    if (get_post_type($post_id) !== 'polls') {
        return;
    }

    $original_post_id = apply_filters('wpml_object_id', $post_id, 'polls', true, 'en');

    if (!$original_post_id) {
        $original_post_id = $post_id; 
    }

    $poll_start_date = get_field('poll_start_date', $post_id);
    $poll_end_date = get_field('poll_end_date', $post_id);
    $poll_show_until_date = get_field('poll_show_until_date', $post_id);
    $poll_type = get_field('poll_type', $post_id);
    $poll_active = get_field('poll_active', $post_id);

    if (!$poll_start_date || !$poll_end_date || !$poll_show_until_date || !$poll_type || $poll_active === null) {
        error_log("Missing required ACF fields for post ID: $post_id");
        return;
    }

    $wpdb->query($wpdb->prepare(
        "INSERT INTO {$wpdb->prefix}polls (original_post_id, poll_start_date, poll_end_date, poll_show_until_date, poll_type, poll_active)
        VALUES (%d, %s, %s, %s, %s, %d)
        ON DUPLICATE KEY UPDATE
        poll_start_date = %s,
        poll_end_date = %s,
        poll_show_until_date = %s,
        poll_type = %s,
        poll_active = %d",
        $original_post_id,
        $poll_start_date,
        $poll_end_date,
        $poll_show_until_date,
        $poll_type,
        $poll_active,
        $poll_start_date,
        $poll_end_date,
        $poll_show_until_date,
        $poll_type,
        $poll_active
    ));

    $translations = apply_filters('wpml_get_element_translations', null, apply_filters('wpml_element_trid', null, $post_id, 'post_polls'));

    if (!$translations) {
        error_log("No translations found for post ID: $post_id");
        return;
    }

    foreach ($translations as $translation) {
        if (!isset($translation->element_id)) {
            error_log("Translation is missing element_id: " . print_r($translation, true));
            continue;
        }
    
        $translated_post_id = $translation->element_id;

        if ($translated_post_id == $post_id) {
            continue;
        }
    
        if (!get_post($translated_post_id)) {
            error_log("Invalid translation ID: $translated_post_id for TRID: $trid");
            continue;
        }

        update_field('poll_start_date', $poll_start_date, $translated_post_id);
        update_field('poll_end_date', $poll_end_date, $translated_post_id);
        update_field('poll_show_until_date', $poll_show_until_date, $translated_post_id);
        update_field('poll_type', $poll_type, $translated_post_id);
        update_field('poll_active', $poll_active, $translated_post_id);
    }
    
}

add_action('acf/save_post', 'sync_poll_settings_to_db', 20);



// Delete DB entries on post deletion
function handle_poll_post_trash($post_id) {
    global $wpdb;

    if (get_post_type($post_id) !== 'polls') {
        return;
    }

    $original_poll_id = apply_filters('wpml_object_id', $post_id, 'polls', true, 'en');
    if (!$original_poll_id) {
        $original_poll_id = $post_id; 
    }

    $polls_table = $wpdb->prefix . 'polls';
    $wpdb->update($polls_table, ['poll_active' => 0], ['original_post_id' => $original_poll_id]);

    error_log("Marked poll as inactive for poll ID: $original_poll_id (trashed).");
}


function handle_poll_post_delete($post_id) {
    global $wpdb;

    if (get_post_type($post_id) !== 'polls') {
        return;
    }

    $original_poll_id = apply_filters('wpml_object_id', $post_id, 'polls', true, 'en');
    if (!$original_poll_id) {
        $original_poll_id = $post_id; 
    }

 
    $polls_table = $wpdb->prefix . 'polls';
    $wpdb->delete($polls_table, ['original_post_id' => $original_poll_id]);

    $votes_table = $wpdb->prefix . 'poll_votes';
    $wpdb->delete($votes_table, ['poll_id' => $original_poll_id]);

    error_log("Deleted poll settings and votes for poll ID: $original_poll_id (permanently deleted).");
}

add_action('wp_trash_post', 'handle_poll_post_trash');
add_action('before_delete_post', 'handle_poll_post_delete');



// Set ACF fields to read only
function set_poll_fields_read_only($field) {
    $read_only_fields = ['field_67443bc1c1677', 'field_67443bde7be05'];

    if (in_array($field['key'], $read_only_fields)) {
        $field['readonly'] = true;
    }

    return $field;
}
add_filter('acf/load_field', 'set_poll_fields_read_only');








