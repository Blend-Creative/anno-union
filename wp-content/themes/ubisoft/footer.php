<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ubisoft
 */

$options = get_fields('option');
?>
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="footer__main">
                    <div class="footer__brand">
                        <img class="logo" src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="Anno Union"/>
                        <p><?php echo get_field('legal_copy', 'option') ?></p>
                        <?php if (!empty($options['footer_buy_buttons'])) : ?>
                            <div class="footer__buttons">
                                <?php foreach ($options['footer_buy_buttons'] as $button) : ?>
                                    <a <?php echo !empty($button['footer_buy_buttons_link']) ? 'href="' . $button['footer_buy_buttons_link'] . '" target="_blank" rel="noopener"' : ''; ?> class="button">
                                        <?php echo $button['footer_buy_buttons_title'] ?? ''; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="footer__menus">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'menu-2',
                            'menu_id' => 'footer-menu-1',
                            'container' => false
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="footer__legal">
                    <p>© <?php echo date('Y'); ?> Ubisoft Entertainment. All rights reserved.</p>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'menu-3',
                        'menu_id' => 'meta-menu',
                        'container' => false
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
