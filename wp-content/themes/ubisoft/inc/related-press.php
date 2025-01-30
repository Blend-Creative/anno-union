<?php
function get_related_press_api()
{
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $cat = filter_input(INPUT_GET, 'cat', FILTER_VALIDATE_INT);
    $exclude = filter_input(INPUT_GET, 'exclude', FILTER_VALIDATE_INT);

    if (!empty($_GET['lang'])) {
        global $sitepress;
        $sitepress->switch_lang($_GET['lang']);
    }

    if (empty($page)) {
        $page = 1;
    }

    if (empty($cat)) {
        $cat = '';
    }

    if (empty($exclude)) {
        $exclude = '';
    }

    $posts = get_posts([
        'posts_per_page' => 5,
        'offset' => ($page - 1) * 4,
        'category' => $cat,
        'exclude' => $exclude,
        'suppress_filters' => false
    ]);

    $result = [];

    if (!empty($posts)) {
        foreach ($posts as $post) {

            $categories = [];
            
            foreach (get_the_category($post->ID) as $index => $category) {
                $categories[] = $category->name;
            }

            $result[] = [
                'dateGlobal' => get_the_time('Y-m-d H:i:s', $post->ID),
                'dateLocal' => date('d.m.Y', strtotime($post->post_date)),
                'image' => get_the_post_thumbnail_url( $post->ID, 'medium', array( 'class' => 'alignleft' ) ),
                'categories' => $categories,
                'title' => $post->post_title,
                'link' => get_the_permalink($post->ID)
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}


add_action('wp_ajax_nopriv_get-related-press-api', 'get_related_press_api');
add_action('wp_ajax_get-related-press-api', 'get_related_press_api');