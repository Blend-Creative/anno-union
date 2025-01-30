<?php
/**
 * Template part for displaying the default header
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ubisoft
 */

$wishlist_url = get_field('wishlist_url', 'option') ?? '/';

?>
<header class="header header--default">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="<?php echo get_home_url() ?>" class="logo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="Anno Union" /></a>
                    <nav>
                        <button class="hamburger-toggle js-toggle-menu">
                            <span></span><span></span><span></span><span></span>
                        </button>

                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'menu-1',
                            'walker' => new Ubisoft_Nav_Walker,
                            'menu_id' => 'primary-menu',
                            'container_class' => 'menu-menu-container'
                        ));
                        ?>

                        <form action="<?php echo ICL_LANGUAGE_CODE !== 'en' ? '/' . ICL_LANGUAGE_CODE : '' ?>/" class="nav-search" data-close-text="<?php echo ICL_LANGUAGE_CODE == 'de' ? 'Zurück' : 'Back'; ?>">
                            <input type="text" name="s" value="">
                            <input type="hidden" name="lang" value="<?php echo(ICL_LANGUAGE_CODE); ?>"/>
                            <button>
                                <svg width="19" height="19" viewBox="0 0 19 19" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor" d="M8 16C9.77498 15.9996 11.4988 15.4054 12.897 14.312L17.293 18.708L18.707 17.294L14.311 12.898C15.405 11.4997 15.9996 9.77544 16 8C16 3.589 12.411 0 8 0C3.589 0 0 3.589 0 8C0 12.411 3.589 16 8 16ZM8 2C11.309 2 14 4.691 14 8C14 11.309 11.309 14 8 14C4.691 14 2 11.309 2 8C2 4.691 4.691 2 8 2Z"/>
                                </svg>
                            </button>
                        </form>

                        <div class="nav-side">
                            <a href="/<?php echo ICL_LANGUAGE_CODE ?>/login" class="button outline" data-button="login" style="display: none"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Anmelden' : 'Login' ?></a>
                            <a href="/" class="button outline" data-button="logout" style="display: none">Logout</a>
                            <a href="<?php echo $wishlist_url; ?>" class="button" target="_blank" rel="noopener"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Wunschliste' : 'Wishlist' ?></a>
                            <?php do_action('wpml_add_language_selector'); ?>
                        </div>
                        <?php /* <div class="darkmode-toggler" id="darkmode-toggler"></div> */ ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>


<div class="mobile-nav" id="nav">
    <div>
        <div class="mobile-nav-heading">
            <a href="<?php echo get_home_url() ?>" class="logo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="Anno Union" /></a>
            <button class="hamburger-toggle js-toggle-menu">
                <span></span><span></span><span></span><span></span>
            </button>
        </div>

        <div class="mobile-nav-meta">
            <?php do_action('wpml_footer_language_selector') ?>
            <div class="mobile-nav-meta-btn">
                <a href="/<?php echo ICL_LANGUAGE_CODE ?>/login" class="button outline" data-button="login" style="display: none"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Anmelden' : 'Login' ?></a>
                <a href="/" class="button outline" data-button="logout" style="display: none">Logout</a>
                <a href="<?php echo $wishlist_url; ?>" class="button"><?php echo ICL_LANGUAGE_CODE == 'de' ? 'Wunschliste' : 'Wishlist' ?></a>
            </div>
        </div>

        <?php
        wp_nav_menu(array(
            'theme_location' => 'menu-1',
            'walker' => new Ubisoft_Nav_Walker_Mobile,
            'menu_id' => 'mobile-nav-main',
            'container_class' => 'mobile-nav-main'
        ));
        ?>

        <form action="<?php echo ICL_LANGUAGE_CODE !== 'en' ? '/' . ICL_LANGUAGE_CODE : '' ?>/" class="nav-search" data-close-text="<?php echo ICL_LANGUAGE_CODE == 'de' ? 'Zurück' : 'Back'; ?>">
            <input type="text" name="s" value="" placeholder="<?php echo ICL_LANGUAGE_CODE == 'de' ? 'Suchen ...' : 'Search ...' ?>">
            <input type="hidden" name="lang" value="<?php echo(ICL_LANGUAGE_CODE); ?>"/>
            <button></button>
        </form>
    </div>
</div>
