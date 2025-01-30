<?php
/**
 * The page template for a single game
 * Template Name: Game
 * Template Post Type: games
 *
 * @package ubisoft
 */

get_header();

?>
    <div class="single-game">

        <?php while (have_posts()) :
            the_post();
            ?>

            <?php
            get_template_part('template-parts/content', 'page');
        endwhile;
        ?>

    </div>

<?php
get_footer();

