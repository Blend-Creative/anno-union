<?php
/**
 * VC Module: Headline
 */

class ubisoft_article_teaser extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_article_teaser', array($this, 'element_frontend'));
    }

    /**
     * Element mapping.
     */
    public function element_mapping()
    {

        // Stop all if VC is not enabled
        if (!defined('WPB_VC_VERSION')) {
            return;
        }

        // Map the block with vc_map()
        vc_map(
            array(
                'name' => _x('Article Teaser', 'visualcomposer'),
                'description' => _x('Creates a "Article Teaser" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_article_teaser',
                'params' => array(
                    array(
                        'type' => 'param_group',
                        'value' => '',
                        'param_name' => 'members',
                        // Note params is mapped inside param-group:
                        'params' => array(
                            array(
                                'type' => 'textarea',
                                'heading' => _x('Title', 'visualcomposer'),
                                'param_name' => 'title',
                                'admin_label' => true
                            ),
                            array(
                                'type' => 'my_link',
                                'heading' => _x('Link', 'visualcomposer'),
                                'param_name' => 'my_link'
                            )
                        )
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Padding Top', 'visualcomposer'),
                        'param_name' => 'padding_top',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => _x('Padding Bottom', 'visualcomposer'),
                        'param_name' => 'padding_bottom',
                    )
                ),
                'admin_enqueue_js' => get_template_directory_uri() . '/assets/backend.js',
                'js_view' => 'CustomElementView',
                'custom_markup' => $this->element_backend(),
            )
        );
    }

    /**
     * Element backend.
     * {{ params }} are rendered in get_template_directory_uri() . '/assets/backend.js
     */
    public function element_backend()
    {
        $html = '<div class="module module-headline">
			<h3>Article Teaser</h3>
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $membersMarkup = '';

        if (!empty($atts['members'])) {
            $members = vc_param_group_parse_atts($atts['members']);
            foreach ($members as $index => $member) {
                $link = parse_my_link_vars(empty($member['my_link']) ? null : $member['my_link']);
                $membersMarkup .= '
          <div class="col-md-4 section-card-wrapper">
            <a href="' . $link['href'] . '" class="section-card" title="' . $link['text'] . '" target="' . $link['target'] . '">
              <h3 class="h3">' . $member['title'] . '</h3>
              <div class="text-center text-md-left">
                <span class="button dark outline"><span>' . get_field('read_more', 'option') . '</span></span>
              </div>
            </a>
          </div>
        ';
            }
        }

        $wrapper_class = make_section_classes($atts, 'section-cards section-grey homepage-cards');

        $html = '
      <section class="' . $wrapper_class . '">
        <div class="container">
          <div class="row">
            ' . $membersMarkup . '
          </div>
        </div>
      </section>
    ';

        return $html;
    }
}

new ubisoft_article_teaser();
