<?php
/**
 * VC Module: Headline
 */

class ubisoft_work extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_work', array($this, 'element_frontend'));
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
                'name' => _x('Work at Ubisoft', 'visualcomposer'),
                'description' => _x('Creates a "Work at Ubisoft" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_work',
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Image', 'visualcomposer'),
                        'param_name' => 'image_id',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Sub-Title', 'visualcomposer'),
                        'param_name' => 'sub_title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Text', 'visualcomposer'),
                        'param_name' => 'copy',
                    ),
                    array(
                        'type' => 'vc_link',
                        'heading' => _x('Link #1', 'visualcomposer'),
                        'param_name' => 'link_1',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('"Vacancies" Text', 'visualcomposer'),
                        'param_name' => 'vacancies_text',
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
     * {{ params }} are rendered in /assets/backend.js
     */
    public function element_backend()
    {
        $html = '<div class="module module-headline">
			<h3>Work at Ubisoft</h3>
			{{ params.title }}<br />
			{{ params.sub_title }}<br />
			<br />
			{{ params.copy }}
		</div>';
        return $html;
    }

    /**
     * Element frontend.
     */
    public function element_frontend($atts, $content = null)
    {
        if (isset($atts['link_1'])) {
            $atts['link_1'] = vc_build_link($atts['link_1']);
        }
        $title = 'Working at Ubisoft';
        $subtitle = 'Jobs';
        $copy = 'If you love games and want to become part of an industry leader with an excellent work environment: Join our studio in Berlin.';
        if (isset($atts['image_id'])) {
            $image = wp_get_attachment_image_url($atts['image_id'], 'xxl');
        } else {
            $image = get_template_directory_uri() . '/assets/images/work.jpg';
        }

        if (isset($atts['title'])) {
            $title = $atts['title'];
        }
        if (isset($atts['sub_title'])) {
            $subtitle = $atts['sub_title'];
        }
        if (isset($atts['copy'])) {
            $copy = $atts['copy'];
        }
        if (isset($atts['link_1'])) {
            $buttonText = $atts['link_1']['title'];
        } else {
            $buttonText = __('All Jobs', 'visualcomposer');
        }
        if (isset($atts['vacancies_text'])) {
            $vacanciesText = $atts['vacancies_text'];
        } else {
            $vacanciesText = 'Vacancies';
        }
        $jobsLink = $atts['link_1']['url'];
        $upload_dir = __DIR__ .'/../../../../uploads';
        // Get latest jobs for this location from the feed.
        $site = get_field('entity', 'option');
        $allJobs = false;
        if ($site == 'bluebyte') {
            $allJobs = array_merge(json_decode(file_get_contents($upload_dir . '/jobs.ber.json'), true), json_decode(file_get_contents($upload_dir . '/jobs.dus.json'), true), json_decode(file_get_contents($upload_dir . '/jobs.mai.json'), true));
        } elseif ($site == 'berlin') {
            $allJobs = json_decode(file_get_contents($upload_dir . '/jobs.ber.json'), true);
        } elseif ($site == 'dusseldorf') {
            $allJobs = json_decode(file_get_contents($upload_dir . '/jobs.dus.json'), true);
        } elseif ($site == 'mainz') {
            $allJobs = json_decode(file_get_contents($upload_dir . '/jobs.mai.json'), true);
        }

        $allJobs = !$allJobs || count($allJobs) === 0 ? [] : $allJobs;

        $latestJobTitle = '';
        $latestJobLabel = '';
        $latestJobMeta = '';
        $latestJobLink = '';

        $latestJobs = [];

        foreach ($allJobs as $index => $job) {
            if ($index == 0) {
                $latestJobTitle = $job['title'];
                $latestJobLabel = $job['function']['label'];
                $latestJobMeta = $job['location']['city'] . ' | ' . $job['type_of_employment'];
                $latestJobLink = str_replace('?oga=true', '', $job['apply_url']);
            } elseif ($index <= 3) {
                $latestJobs[] = '<li><a target="_blank" href="' . str_replace('?oga=true', '', $job['apply_url']) . '">' . $job['title'] . '</a></li>';
            } else {
                break;
            }
        }

        $wrapper_class = make_section_classes($atts, 'section-jobs');

        $vacanciesHtml = '<div class="section-jobs-vacancies">
              <h2>' . $vacanciesText . '</h2>

              <ul>
                ' . implode($latestJobs, '') . '
              </ul>
            </div>';

        if ($site == 'mainz') {
            $vacanciesHtml = '';
        }

        $html = '
    <section class="' . $wrapper_class . '">
      <div class="container">
        <div class="row">
          <div class="col-md-5">
            <h2 class="h4">' . $subtitle . '</h2>
            <h3 class="h3 is-underlined"><a href="' . $jobsLink . '">' . $title . '</a></h3>
          </div>

          <div class="col-md-6 d-none d-md-block">
            <a href="' . $jobsLink . '"><img class="img-fluid ctx-1 img-shadow" src="' . $image . '"></a>
          </div>
        </div>

        <style>
            @media (min-width: 1000px) {
                .ctx-1 {
                    width: 100%; height: auto;
                }
            }
            .ctx-1 {
                max-width: 100%;
            }
        </style>

        <div class="row">
          <div class="col-md-3 order-md-first mb-5">
            <p class="section-jobs-intro">' . $copy . '</p>
            <div class="text-center text-md-left">
                <a href="' . $jobsLink . '" class="button studio"><span>' . $buttonText . '</span></a>
            </div>
          </div>

          <div class="col-md-6 d-block d-md-none order-md-2">
             <a href="' . $jobsLink . '"><img class="img-fluid img-shadow" src="' . $image . '"></a>
          </div>

          <div class="col-md-4 offset-md-1 order-last order-md-3">
            '. $vacanciesHtml .'
          </div>

          <div class="col-md-4 order-3 order-md-last">
            <a target="_blank" href="' . $latestJobLink . '" class="section-jobs-panel">
              <div>
                <h2 class="h3">' . $latestJobTitle . '</h2>
                <p>' . $latestJobLabel . '</p>
              </div>

              <div class="homepage-job-panel-footer">' . $latestJobMeta . '</div>
            </a>
          </div>
        </div>
      </div>
    </section>
    ';

        return $html;
    }
}

new ubisoft_work();
