<?php
/**
 * Template part for displaying the social channels module
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ubisoft
 */

$options = get_fields('option');

if (!empty($options['social_channels_buttons'])) : ?>
<div class="social-channels">
    <div class="container">
        <div class="social-channels__header">
            <?php if (!empty($options['social_channels_overline'])) : ?>
                <p><strong><?php echo $options['social_channels_overline']; ?></strong></p>
            <?php endif; ?>
            <?php if (!empty($options['social_channels_headline'])) : ?>
                <h2 class="h2 smaller"><?php echo $options['social_channels_headline']; ?></h2>
            <?php endif; ?>
            <?php if (!empty($options['social_channels_text'])) : ?>
                <p class="text-larger"><?php echo $options['social_channels_text']; ?></p>
            <?php endif; ?>
        </div>
        <div class="social-channels__links">
            <?php foreach($options['social_channels_buttons'] as $button) : ?>
                <?php if (!empty($button['component_button_show_teaser'])) : ?>
                    <a <?php echo !empty($button['social_channels_button_link']) ? 'href="' . $button['social_channels_button_link'] . '" target="_blank" rel="noopener"' : ''; ?>>
                        <?php if (!empty($button['social_channels_button_icon'])) : ?>
                            <img src="<?php echo $button['social_channels_button_icon']; ?>" alt="<?php echo $button['social_channels_button_title'] ?? ''; ?>" />
                        <?php endif; ?>
                        <?php if (!empty($button['social_channels_button_title'])) : ?>
                            <span><?php echo $button['social_channels_button_title']; ?></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif;
