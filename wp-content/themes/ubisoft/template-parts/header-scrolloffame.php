<?php
/**
 * Template part for displaying the scrolloffame header
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ubisoft
 */

global $post;

$wishlist = get_field('wishlist_url', 'option') ?? '/';
$trailer = get_field('trailer', $post->ID);
$backlink = get_field('backlink', $post->ID) ?? false;

?>
<header class="header header--scrolloffame">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="<?php echo !empty($backlink) ? $backlink : get_home_url(); ?>" class="back">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M21,11H6.4l5.3-5.3l-1.4-1.4L2.6,12l7.7,7.7l1.4-1.4L6.4,13H21V11z"/>
                        </svg>
                        <span><?php echo ICL_LANGUAGE_CODE !== 'en' ? '<span class="d-none d-md-inline">Zurück zur </span>Startseite' : '<span class="d-none d-md-inline">Back to </span>Homepage' ?></span>
                    </a>
                    <a href="<?php echo get_home_url() ?>" class="logo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-anno117.webp?v2" alt="Anno 117 Pax Romana" /></a>
                    <nav>
                        <a href="<?php echo $wishlist; ?>" class="button dark" target="_blank" rel="noopener"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Wunschliste' : 'Wishlist' ?></a>
                        <?php if (!empty($trailer)) : ?>
                            <a href="<?php echo get_permalink($trailer); ?>" class="button dark outline" data-toggle="modal" data-target="#trailer" data-video-modal-button><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Trailer ansehen' : 'Watch Trailer' ?></a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
