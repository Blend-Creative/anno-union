<?php
/**
 * VC Module: Video meta box
 */
use chillerlan\QRCode\{QRCode, QROptions};

class ubisoft_video_meta extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_video_meta', array($this, 'element_frontend'));
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
                'name' => _x('Video Meta Box', 'visualcomposer'),
                'description' => _x('Creates a video meta section containing video-specific information.', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_video_meta',
                'params' => array(),
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
			<h3>Video Meta Box</h3>
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        $wrapper_class = make_section_classes($atts, '');

        $location_html = null;
        $date_html = null;
        $qr_code_html = null;
        $calendar_button_html = null;

        if ($location = get_field('location')) {
            $location_html = '<p><strong>Ort</strong><br>' . $location . '</p>';
        }
        if (get_field('date_from') && get_field('date_end')) {
            $data = "BEGIN:VEVENT\nSUMMARY:" . get_the_title() . "\nLOCATION:" . get_field('location') . "\nURL:" . get_permalink() . "\nDESCRIPTION:\nDTSTART:" . date('Ymd\THis', strtotime(get_field('date_from'))) . "\nDTEND:" . date('Ymd\THis', strtotime(get_field('date_end'))) . "\nEND:VEVENT";
            $date_html = '<p><strong>Datum</strong><br>Von: ' . date('d.m.Y H:i:s', strtotime(get_field('date_from'))) . '<br>Bis: ' . date('d.m.Y H:i:s', strtotime(get_field('date_end'))) . '</p>';
            $qr_code_html = '<img src="' . (new QRCode)->render($data) . '" style="margin: 0 0 10px -10px; width: 150px; height: auto;" /><p>Scannen um zum Kalender hinzuzufügen</p>';
            $calendar_button_html = "<a target='_blank' class='button calendar-button' href='http://www.google.com/calendar/event?action=TEMPLATE&text=" . get_the_title() . "&dates=" . date('Ymd\THis', strtotime(get_field('date_from'))) . "/" . date('Ymd\THis', strtotime(get_field('date_end'))) . "&details=" . get_permalink() . "&location=" . get_field('location') . "'>Zum Kalender hinzufügen</a>";
        }

        return '<div class="container">
            <div class="row">
                <div class="col-md-6">
                    ' . $location_html . '
                    ' . $date_html . '
                </div>
                <div class="col-md-6">
                    ' . $qr_code_html . '
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    ' . $calendar_button_html . '
                </div>
            </div>
        </div>';
    }
}

new ubisoft_video_meta();
