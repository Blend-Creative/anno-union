<div class="col-lg-4 col-md-6">
    <div class="post-listing-item">
        <div class="post-listing-image">
            <?php echo get_the_post_thumbnail(get_the_ID()); ?>
        </div>
        <h3 lcass="post-listing-title">
            <a href="<?php echo get_the_permalink(); ?>">
                <?php echo get_the_title(); ?>
            </a>
        </h3>

        <div class="post-listing-excerpt">
            <?php the_field('press_intro'); ?>
        </div>

        <div class="post-listing-footer">
            <time class="post-listing-date" datetime="<?php the_time('d.m.Y'); ?>"><?php the_time('d.m.Y'); ?></time>
            <div class="post-listing-pipe"></div>
            <div class="post-listing-categories">
                <?php $post_cats = wp_get_post_categories(get_the_ID()); ?>
                <?php foreach( $post_cats as $category ): ?>
                    <a href="<?= get_category($category)->slug; ?>"><?= get_category($category)->name; ?></a>
                <?php endforeach; ?>
            </div>
            <div class="post-listing-arrow">
                <a href="<?php echo get_the_permalink(); ?>">
                    <img src="<?= get_bloginfo('stylesheet_directory') . '/images/arrow-right-black.svg'; ?>" />
                </a>
            </div>
        </div>

    </div>
</div>