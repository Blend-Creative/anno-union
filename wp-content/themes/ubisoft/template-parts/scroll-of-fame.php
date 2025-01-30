<?php
/**
 * The template for the Scroll of Fame
 * Template Name: Scroll of Fame
 *
 * @package ubisoft
 */

global $post;
$trailer = get_field('trailer', $post->ID);
$embed = !empty($trailer) ? get_field('embed', $trailer) : false;
$intro = get_field('intro_copy', $post->ID) ?? false;
$outro = get_field('outro_copy', $post->ID) ?? false;

$comments = [];
$comments_file = get_field('comments', $post->ID) ?? false;
$skip_rows = get_field('comments_skip_rows', $post->ID) ?? 0;
if ($comments_file) {
    $url = get_attached_file($comments_file['ID']);
    $file = fopen($url, 'r');
    $i = 0;
    while (($row = fgetcsv($file, 10000, ",")) !== FALSE) {
        if ($i >= $skip_rows) {
            $comments[] = $row;
        }
        $i += 1;
    }
}

get_header();
?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="scrolloffame" data-scrolloffame>
                <?php get_template_part( 'template-parts/header', 'scrolloffame' ); ?>

                <div class="scrolloffame__clouds">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_SHXX_Scroll_of_fame_Comp_v001_Cloud_01_fG.webp" alt="" class="scrolloffame__clouds__cloud scrolloffame__clouds__cloud--01" />
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_SHXX_Scroll_of_fame_Comp_v001_Cloud_02_fG.webp" alt="" class="scrolloffame__clouds__cloud scrolloffame__clouds__cloud--02" />
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_SHXX_Scroll_of_fame_Comp_v001_Cloud_03_fG.webp" alt="" class="scrolloffame__clouds__cloud scrolloffame__clouds__cloud--03" />
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_SHXX_Scroll_of_fame_Comp_v001_Cloud_04_fG.webp" alt="" class="scrolloffame__clouds__cloud scrolloffame__clouds__cloud--04" />
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_SHXX_Scroll_of_fame_Comp_v001_Cloud_05_fG.webp" alt="" class="scrolloffame__clouds__cloud scrolloffame__clouds__cloud--05" />
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_SHXX_Scroll_of_fame_Comp_v001_Cloud_06_fG.webp" alt="" class="scrolloffame__clouds__cloud scrolloffame__clouds__cloud--06" />
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_SHXX_Scroll_of_fame_Comp_v001_Cloud_07_fG.webp" alt="" class="scrolloffame__clouds__cloud scrolloffame__clouds__cloud--07" />
                </div>

                <?php if (sizeof($comments) > 0) : ?>
                    <div class="scrolloffame__inner" data-scrolloffame-inner>
                        <div class="scrolloffame__roll scrolloffame__roll--top" data-scrolloffame-top>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_Scroll_LSR_v005_Rolle_oben_fG_placeholder.png" alt="" class="scrolloffame__roll__placeholder" />
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_Scroll_LSR_v007_Rolle_oben_fG_00000.webp" alt="" class="scrolloffame__roll__image" data-scrolloffame-top-image />
                        </div>
                        <div class="scrolloffame__paper" data-scrolloffame-paper>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_Scroll_LSR_v006_Plane_shadow_fG.webp" alt="" class="scrolloffame__shadow" data-scrolloffame-shadow />
                            <div class="scrolloffame__list" data-scrolloffame-list>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/logo.svg?v2" alt="Scroll of Fame" class="scrolloffame__logo" />
                                <?php if (!empty($intro)) : ?>
                                    <p class="scrolloffame__intro"><?php echo $intro; ?></p>
                                <?php endif; ?>
                                <?php foreach ($comments as $comment) : ?>
                                    <div class="scrolloffame__item">
                                        <p class="scrolloffame__item__meta"><?php echo !empty($comment[3]) ? $comment[3] : ''; ?> <?php echo !empty($comment[5]) ? '· ' . $comment[5] : ''; ?> <?php echo !empty($comment[4]) ? '· ' . $comment[4] : ''; ?></p>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/separator-01.svg" alt="" class="scrolloffame__item__separator scrolloffame__item__separator--01" />

                                        <p class="scrolloffame__item__comment">
                                            <?php echo !empty($comment[1]) ? nl2br($comment[1]) : ''; ?>
                                        </p>
                                        <?php /*
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/separator-01.svg" alt="" class="scrolloffame__item__separator scrolloffame__item__separator--03" />
                                        <p class="scrolloffame__item__answer">
                                            <?php echo !empty($comment[2]) ? nl2br($comment[2]) : ''; ?>
                                        </p>
                                        */ ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/separator-02.svg" alt="" class="scrolloffame__item__separator scrolloffame__item__separator--02" />
                                    </div>
                                <?php endforeach; ?>
                                <?php if (!empty($outro)) : ?>
                                    <p class="scrolloffame__outro"><?php echo $outro; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="scrolloffame__roll scrolloffame__roll--bottom" data-scrolloffame-bottom>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_Scroll_LSR_v005_Rolle_unten_fG_placeholder.png" alt="" class="scrolloffame__roll__placeholder" />
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/UB23I13_Scroll_LSR_v007_Rolle_unten_fG_00000.webp" alt="" class="scrolloffame__roll__image" data-scrolloffame-bottom-image />
                        </div>
                        <?php /*
                        <div class="scrolloffame__replay">
                            <button type="button" class="button dark" data-scrolloffame-replay><?php _e('Restart', 'ubisoft'); ?></button>
                        </div>
                        */ ?>
                    </div>
                <?php endif; ?>

                <div class="scrolloffame__preloader" data-scrolloffame-preloader>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-of-fame/loading.svg?v2" alt="" />
                </div>

            </div>

            <?php if (!empty($embed)) : ?>
                <div class="modal fade" id="trailer" tabindex="-1" aria-hidden="true" data-video-modal>
                    <div class="modal-dialog modal-dialog-centered modal-xl modal-video">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="currentColor" d="M18.7,6.7l-1.4-1.4L12,10.6L6.7,5.3L5.3,6.7l5.3,5.3l-5.3,5.3l1.4,1.4l5.3-5.3l5.3,5.3l1.4-1.4L13.4,12L18.7,6.7z"/></svg>
                                </button>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <?php echo str_replace('src=', 'data-src=', $embed); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
<?php
get_sidebar();
get_footer();
