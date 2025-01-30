<?php
/**
 * The template for press archive
 * @package ubisoft
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
            <?php
            if(is_home() && get_option('page_for_posts')) {
                $content_post = get_post(get_option('page_for_posts'));
                $content = $content_post->post_content;
                $content = apply_filters('the_content', $content);
                $content = str_replace(']]>', ']]&gt;', $content);
                echo $content;
            }
            ?>

            <div class="container">
                <?php
                    if ( function_exists('yoast_breadcrumb') ) {
                    yoast_breadcrumb( '<div class="breadcrumbs">','</div>' );
                    }
                    ?>
            </div>

            <div id="press-archive-main" class="archive-wrapper">
                <div class="container post-filter-wrapper">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="dropdown-wrapper">
                                <?php $categories = get_categories(); ?>
                                <select id="blog-category-filter">
                                    <option value=""><?php _e('select by relevance', 'ubisoft') ?></option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="daterangepicker-wrapper">
                                <input id="blog-daterange-filter" autocomplete="off" value="<?php _e('select by date', 'ubisoft'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="tag-cloud">
                                <?php
                                    $args = array(
                                        'taxonomy' => array( 'post_tag' ),
                                    );

                                    wp_tag_cloud( $args );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container post-listing">
                    <div class="row post-listing-row">
                        <?php while (have_posts()) : the_post(); ?>
                        <?php if (get_the_ID() == 37): ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="post-listing-item post-event" style="background-image:url(<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>)">
                                    <p class="post-listing-event-date">17.08.2020 | 14:00 – 17:00 Uhr</p>
                                    <h3 lcass="post-listing-title">
                                        <a href="<?php echo get_the_permalink(); ?>">
                                            <?php echo get_the_title(); ?>
                                        </a>
                                    </h3>
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
                                                <img src="<?= get_bloginfo('stylesheet_directory') . '/images/arrow-right.svg'; ?>" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- <div class="col-lg-3 col-md-6"> -->
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
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </div>
                    <div class="row archive-more-row">
                        <div class="col-md-12">
                            <div class="text-center">
                                <?php
                                $current_page = !empty($_GET['lmpage']) ? intval($_GET['lmpage']) : 0;
                                next_posts_link_of_page(__('Load more', 'ubisoft'), $current_page);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
