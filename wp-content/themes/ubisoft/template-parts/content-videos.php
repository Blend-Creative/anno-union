<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ubisoft
 */
?>
<div class="single-post" style="background: white">
    <div class="imprint-hero" style="padding-bottom: 50px;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <?php
                    $title_size = get_field('press_title_font_size', $post->ID);
                    $title_size = empty($title_size) ? 'title-size-l' : 'title-size-' . $title_size;
                    ?>
                    <h2 class="h1"><span class="<?php echo $title_size; ?>"><?php echo get_the_title() ?></span></h2>
                    <h3 class="h4"><?php echo strip_tags(get_the_excerpt()) ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

