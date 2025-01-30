<?php

/**
 * Plugin Name: Anno users
 * Plugin URI: https://www.anno-union.com/
 * Description: Extended User settings
 * Version: 1.0.0
 * Author: Jens Büchel
 * Author URI: http://www.jensbuechel.de
 * Text Domain: anno-users
 * Domain Path: /languages
 */




/*
 * Create new columns for Banned Status.
 * @param array $columns Array of all user table columns {column ID} => {column Name}
 */
add_filter( 'manage_users_columns', 'anno_modify_user_table' );

function anno_modify_user_table( $columns ) {

	$columns['comments'] = 'Comments';

	$columns['banned'] = 'User is Banned';
	$columns['banned_date'] = 'Banned date';
	$columns['unbanned_date'] = 'Unbanned date';

	return $columns;

}

/*
 *
 * @param string $row_output text/HTML output of a table cell
 * @param string $column_id_attr column ID
 * @param int $user user ID (in fact - table row ID)
 */
add_filter( 'manage_users_custom_column', 'anno_modify_user_table_row', 10, 3 );

function commentCount($user_id) {
	global $wpdb;
	$count = $wpdb->get_var('SELECT COUNT(comment_ID) FROM ' . $wpdb->comments. ' WHERE user_id = ' . $user_id);
	return $count;
}

function anno_modify_user_table_row( $row_output, $column_id_attr, $user ) {

	$date_format = 'j M, Y';
	switch ( $column_id_attr ) {
		case 'comments' :
			$commentCounter = commentCount($user);
			return $commentCounter;
			break;
		case 'banned' :
			    $bannedStatus = (get_the_author_meta( 'banned', $user ) == 1) ? 'ja': 'nein';
				return $bannedStatus;
			break;
		case 'banned_date' :
		    $bannedDate = get_the_author_meta( 'banned_date', $user );
			$bannedDateFormatted = '-';
		    if(!empty($bannedDate)){
		        $bannedDateFormatted = date( $date_format, strtotime( $bannedDate ) );
		    }
			return $bannedDateFormatted;
			break;
		case 'unbanned_date' :
			$unbannedDate = get_the_author_meta( 'unbanned_date', $user );
			$unbannedDateFormatted = '-';
		    if(!empty($unbannedDate)){
			    $unbannedDateFormatted = date( $date_format, strtotime( $unbannedDate ) );
		    }
			return $unbannedDateFormatted;
			break;
		default:
	}

	return $row_output;

}

/*
 * Make our "Registration date" column sortable
 * @param array $columns Array of all user sortable columns {column ID} => {orderby GET-param}
 */
add_filter( 'manage_users_sortable_columns', 'anno_make_registered_column_sortable' );

function anno_make_registered_column_sortable( $columns ) {
	$columns['comments'] = 'Comments';
	$columns['banned'] = 'Banned';
	#$columns['banned_date'] = 'Banned date';

	return $columns;
	//return wp_parse_args( array( 'post' => 'registered' ), $columns );
}

add_action( 'show_user_profile', 'anno_user_profile_fields' );
add_action( 'edit_user_profile', 'anno_user_profile_fields' );

function anno_user_profile_fields( $user ) { ?>
	<h3><?php _e("Banned information", "blank"); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="banned"><?php _e("Banned"); ?></label></th>
			<td>
				<input type="checkbox" name="banned" id="banned" value="1" <?php if( esc_attr( get_the_author_meta( 'banned', $user->ID ) ) == 1) { echo 'checked="checked"'; } ?> class="regular-text" /><br /><span class="description"><?php _e("The User is banned."); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="banned_date"><?php _e("Banned Start-Date"); ?></label></th>
			<td>
				<input type="date" name="banned_date" id="banned_date" value="<?php echo esc_attr( get_the_author_meta( 'banned_date', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e("Startdate/time for banned user."); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="unbanned_date"><?php _e("Banned End-Date"); ?></label></th>
			<td>
				<input type="date" name="unbanned_date" id="unbanned_date" value="<?php echo esc_attr( get_the_author_meta( 'unbanned_date', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e("Enddate/time for banned user."); ?></span>
			</td>
		</tr>
	</table>
<?php }

function anno_user_profile_save($userId) {
	if (!current_user_can('edit_user', $userId)) {
		return;
	}

	update_user_meta($userId, 'banned', $_REQUEST['banned']);
	update_user_meta($userId, 'banned_date', $_REQUEST['banned_date']);
	update_user_meta($userId, 'unbanned_date', $_REQUEST['unbanned_date']);
}

add_action('personal_options_update', 'anno_user_profile_save');
add_action('edit_user_profile_update', 'anno_user_profile_save');
add_action('user_register', 'anno_user_profile_save');

/**
 * Sort by expire date. Meta key is called 'prefix_expiration_date'.
 *
 * @since  1.0.0
 * @return void
 */
function anno_sort_by_comments( $query ) {
    global $wpdb;
	if ( 'Comments' == $query->get( 'orderby' ) ) {

	}
}
add_action( 'pre_get_users', 'anno_sort_by_comments' );

/**
 * Sort by expire date. Meta key is called 'prefix_expiration_date'.
 *
 * @since  1.0.0
 * @return void
 */
function anno_sort_by_banned( $query ) {

	if ( 'Banned' == $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'banned' );
		$query->set( 'meta_key', 'banned' );
	}
}
add_action( 'pre_get_users', 'anno_sort_by_banned' );
