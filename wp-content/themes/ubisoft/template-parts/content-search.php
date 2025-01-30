<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ubisoft
 */

?>

<?php
	$post = get_post(get_the_ID());
	$categories = get_the_category($post->ID);
	$category = '<em>';
	foreach ($categories as $index => $_category) {
		if ($index > 0) {
			$category .= ', ' . $_category->cat_name;
		} else {
			$category .= $_category->cat_name;
		}
	}
	$category .= '</em>';

	$postType = $post->post_type;

	$date = date('d.m.Y', strtotime($post->post_date));

	$excerpt = '<p>' . $post->post_excerpt . '</p>';

	$image = get_the_post_thumbnail( $post->ID, 'large', array( 'class' => 'alignleft' ) );

	if ($postType == 'updates') {
		$image = null;
	}

	$specialDate = null;

	if ($postType == 'events' || $postType == 'videos') {
		if ($startDate = get_field('date_from', $post->ID)) {
			$specialDate = '<span data-event-date>' . $startDate . '</span>';
		}
	}

	$meta = '<div class="meta">
		<span>' . $date . '</span>
		<span>' . $category . '</span>
	</div>';

	$postTypeDisplay = '<span>' . $postType . '</span>';

	if ($postType == 'newscategories') {
		$meta = null;
		$postTypeDisplay = null;
	}
?>

<div class="section-post-filter-item <?= $postType === 'videos'?'videos':''; ?>" data-type="<?php echo $postType ?>" data-id="<?php echo $post->ID ?>">
	<?php if ($postType === 'videos' && get_field('embed', $post->ID)): ?>
		<?php $embed = get_field('embed', $post->ID); ?>
		<?php if (shortcode_exists('borlabs-cookie')) : ?>
			<?php if (strpos($embed, 'twitch.tv') !== false) : ?>
				<?php echo do_shortcode('[borlabs-cookie id="twitch" type="content-blocker"]' . $embed . '[/borlabs-cookie]'); ?>
			<?php else : ?>
				<?php echo do_shortcode('[borlabs-cookie id="youtube" type="content-blocker"]' . $embed . '[/borlabs-cookie]'); ?>
			<?php endif; ?>
		<?php else : ?>
			<div class=""><?php echo $embed; ?></div>
		<?php endif; ?>
	<?php endif; ?>
	<a href="<?php echo get_the_permalink($post->ID) ?>">
		<?php echo $image ?>
		<?php echo $specialDate ?>
		<div>
			<?php echo $postTypeDisplay ?>
			<strong><?php echo  get_the_title() ?></strong>
			<?php if ($postType === 'videos'): ?>
				<div class="meta"><span></span><span></span></div>
			<?php else: ?>
				<?php echo $excerpt ?>
				<?php echo $meta ?>
			<?php endif; ?>
		</div>
	</a>
</div>


