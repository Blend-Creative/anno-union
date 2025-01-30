<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ubisoft
 */

?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?> style="--vh:100vh;">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php //favicons ?>
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" sizes="32x32">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/favicon-180x180.png">
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/manifest.json">

    <?php /*<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">*/ ?>
    <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/style.css?v=<?= ASSETS_VERSION ?>"
          as="style">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/style.css?v=<?= ASSETS_VERSION ?>">
    <noscript>
        <link rel="stylesheet"
              href="<?php echo get_template_directory_uri(); ?>/assets/style.css?v=<?= ASSETS_VERSION ?>">
    </noscript>

    <?php if (is_search()): ?>
        <title><?php echo (ICL_LANGUAGE_CODE !== 'de') ? 'Suchergebnisse' : 'Search results' ?></title>
    <?php endif; ?>
    <?php wp_head(); ?>

    <script>
        document.documentElement.classList.remove('no-js');
    </script>
</head>
<body <?php body_class(); ?>>

<div style="position: absolute; top: 0; right: 0; height: var(--vh); left: 0; background: #000; z-index: -1;"></div>

<?php
if ( ! is_page_template( 'template-parts/scroll-of-fame.php' ) ) {
    get_template_part( 'template-parts/header', 'default' );
}
