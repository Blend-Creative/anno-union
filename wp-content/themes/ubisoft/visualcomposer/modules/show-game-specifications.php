<?php
/**
 * VC Module: Show Game Specifications
 */

class ubisoft_show_game_specifications extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_show_game_specifications', array($this, 'element_frontend'));
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
                'name' => _x('System Requirements', 'visualcomposer'),
                'description' => _x('Includes a table based on the game specifications. Formerly known as "Show Game Specifications"', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_show_game_specifications',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'heading',
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
			<h3>System Requirements</h3>
			{{ params.heading }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $heading_html = null;

        if (!empty($atts['heading'])) {
            $heading_html = '<h3 class="h2">' . $atts['heading'] . '</h3>';
        }

        $page_id = get_the_ID();

        $columns = get_field('column_headers', $page_id);
        $rows = get_field('rows', $page_id);

        $table_html = '<div class="table-responsive"><table>';

        if (count($columns) > 0) {
            $table_html .= '<thead>';
            $table_html .= '<tr>';
        }

        // First column must be left empty to allow for row heading.
        $table_html .= '<th></th>';

        foreach ($columns as $index => $column) {
            $table_html .= '<th>' . $column['text'] . '</th>';
        }

        if (count($columns) > 0) {
            $table_html .= '</tr>';
            $table_html .= '</thead>';
        }

        if (count($rows) > 0) {
            $table_html .= '<tbody>';
        }

        foreach ($rows as $index => $row) {
            $table_html .= '<tr>';
            foreach ($row['row'] as $index => $_row) {
                $table_html .= '<td>' . $_row['text'] . '</td>';
            }
            $table_html .= '</tr>';
        }

        if (count($rows) > 0) {
            $table_html .= '</tbody>';
        }

        $table_html .= '</table></div>';

        return '
            <section class="games-specifications">
                <div class="container">
                    ' . $heading_html . '
                    ' . $table_html . '
                </div>
          </section>';
    }
}

new ubisoft_show_game_specifications();
