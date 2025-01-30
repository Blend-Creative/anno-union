<?php
/**
 * Template part for displaying the posts slider
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ubisoft
 */

$variant = $args['variant'] ?? 'default';
$title_color = $args['title_color'] ?? 'color-purple';
$background_color = $args['background_color'] ?? '';
$background_image = !empty($args['background_image']) ? 'background-image: url(' . $args['background_image'] . ');' : '';
$title = $args['title'] ?? '';

// create button markup
$button = '';
if (!empty($args['button'])) {
    $button = '<a href="' . ($args['button']['url'] ?? '') . '" class="button dark" ' . (!empty($args['button']['target']) ? 'target="' . $args['button']['target'] . '"' : '') . ' ' . (!empty($args['button']['rel']) ? 'rel="' . $args['button']['rel'] . '"' : '') . '>' . ($args['button']['title'] ?? '') . '</a>';
}

// create slider markup
$sliderSetup = array();
if ($variant === 'hero') {
    $sliderSetup = array(
        'responsive' => array(
            '1024' => array(
                'fixedWidth' => 400,
                'gutter' => 30,
            )
        )
    );
}

// create items markup
$items = '';
if (!empty($args['items'])) {
    foreach ($args['items'] as $item) {
        $imageSize = $variant === 'hero' ? 'large-portrait' : 'large-landscape';
        $teaserImage = get_field('post_teaser_image', $item->ID);
        if (!empty($teaserImage)) {
            $image = wp_get_attachment_image($teaserImage['id'], $imageSize);
        } else {
            $featuredImage = get_post_thumbnail_id($item->ID);
            $image = wp_get_attachment_image($featuredImage, $imageSize);
        }

        // include tags
        $tags = '';
        if ($variant === 'hero') {
            $tagItems = get_the_tags($item->ID);
            if (!empty($tagItems)) {
                $tags = '<p class="posts-slider__tags">';
                foreach ($tagItems as $index => $tag) {
                    if ($index > 0) {
                        $tags .= '| ';
                    }
                    $tags .= $tag->name . ' ';
                }
                $tags .= '</p>';
            }
        }

        // include excerpt
        $excerpt = '';
        if ($variant === 'default') {
            $excerpt = '<p class="posts-slider__excerpt">' . get_the_excerpt($item->ID) . '</p>';
        }

        // include post date
        $date = '';
        if ($variant === 'default') {
            $date = '<p class="posts-slider__date">' . get_the_date('', $item->ID) . '</p>';
        }

        $items .= '<a href="' . get_permalink($item->ID) . '"><div class="posts-slider__item">
                        <div class="posts-slider__image">
                            ' . $image . '
                        </div>
                        <div class="posts-slider__content">
                            ' . $tags . '
                            ' . $date . '
                            <h3 class="' . ($variant === 'hero' ? 'h3' : 'h4') . '">' . $item->post_title . '</h3>
                            ' . $excerpt . '
                        </div>
                    </div></a>';
    }
}

if (!empty($items)) : ?>
<div class="posts-slider posts-slider--<?php echo $variant; ?> <?php echo $background_color; ?>" data-posts-slider style="<?php echo $background_image; ?>">
    <div class="posts-slider__header">
        <div class="container">
            <?php if (!empty($title)) : ?>
                <h2 class="h2 <?php echo $title_color; ?>"><?php echo $title; ?></h2>
            <?php endif; ?>
            <?php echo $button; ?>
        </div>
    </div>
    <div class="posts-slider__carousel">
        <div class="container">
            <div class="posts-slider__slider" data-posts-slider-carousel='<?php echo json_encode($sliderSetup); ?>'>
                <?php echo $items; ?>
            </div>
        </div>
    </div>
    <?php if (!empty($button)) : ?>
        <div class="posts-slider__footer">
            <div class="container">
                <?php echo $button; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php endif;
