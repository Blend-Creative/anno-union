<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ubisoft
 */
?>
<div class="row single-post">
    <div class="content-text">
        <?php
        $title_size = get_field('press_title_font_size', $post->ID);
        $title_size = empty($title_size) ? 'title-size-l' : 'title-size-' . $title_size;
        ?>
        <h2 class="h1"><span class="<?php echo $title_size; ?>"><?php echo get_the_title() ?></span></h2>
        <h3 class="h4">Placeholder sub-title</h3>
        <p><?php echo strip_tags(get_the_excerpt()) ?></p>
        <p class="single-post-link-wrapper"><a class="single-post-link" href="<?= esc_url( get_permalink() ) ?>"><?= __("Read more") ?></a></p>
    </div>
    <div class="ontent-image">
        <?php ubisoft_post_thumbnail(); ?>
    </div>
</div>
