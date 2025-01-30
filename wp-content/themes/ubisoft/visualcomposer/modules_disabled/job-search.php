<?php
/**
 * VC Module: Headline
 */

class ubisoft_job_search extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_job_search', array($this, 'element_frontend'));
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
                'name' => _x('Job Search', 'visualcomposer'),
                'description' => _x('Includes the job search feature".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_job_search',
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => _x('Job Search Filter', 'visualcomposer'),
                        'param_name' => 'job_search_location',
                        'value' => array(
                            'None' => 'none',
                            'Berlin' => 'Berlin',
                            'Düsseldorf' => 'Düsseldorf',
                            'Mainz' => 'Mainz'
                        ),
                        'std' => 'none'
                    ),
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
			<h3>Job Search</h3>
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
        $allJobs = array_merge(json_decode(file_get_contents(__DIR__ . '/../../../../uploads/jobs.ber.json'), true), json_decode(file_get_contents(__DIR__ . '/../../../../uploads/jobs.dus.json'), true), json_decode(file_get_contents(__DIR__ . '/../../../../uploads/jobs.mai.json'), true));
        $items = '';
        $listings = '';
        $areaOfExpertise = [];
        $typesOfEmployment = [];
        $professionalExperience = [];

        shuffle($allJobs);

        foreach ($allJobs as $index => $job) {
            // echo '<pre>';
            // print_r($job);
            // exit;
            $now = time(); // or your date as well
            $date = strtotime($job['date']);
            $dateDiff = $now - $date;
            $date = round($dateDiff / (60 * 60 * 24));

            if ($date < 1) {
                $date = 'today';
            } elseif ($date < 31) {
                $date = $date . ' days';
            } else {
                $date = $date . ' days';
            }

            $areaOfExpertise[$job['department']['label']] = $job['department']['label'];
            $typesOfEmployment[$job['type_of_employment']] = $job['type_of_employment'];
            $professionalExperience[$job['experience_level']] = $job['experience_level'];

            $items .= '<li data-job="' . $job['id'] . '" data-title="' . $job['title'] . '" data-location="' . $job['location']['city'] . '" data-function="' . $job['function']['label'] . '" data-type="' . $job['type_of_employment'] . '" data-experience-level="' . $job['experience_level'] . '">
				<div class="d-flex align-items center justify-content-between">
					<a href="#" class="cta">' . $job['title'] . '</a>
				</div>

				<div class="jobs-shortlist-location">
					<div>' . $job['location']['city'] . ', ' . $job['type_of_employment'] . '<br>' . $job['id'] . '</div>
				</div>
            </li>';

            $job['ref'] = str_replace('?oga=true', '', $job['ref']);

            $emailBody = str_replace('?oga=true', '', $job['apply_url']) . "%0D%0A%0D%0A" . $job['title'] . "%0D%0A%0D%0A" . $job['location']['city'] . " - " . $job['type_of_employment'] . "%0D%0A%0D%0A" . "JOB DESCRIPTION" . "%0D%0A%0D%0A" . strip_tags($job['job_description']['text']);

            $listings .= '<div class="jobs-job-details" data-listing="' . $job['id'] . '">
				<div class="row">
					<div class="col-md-8">
						<div class="d-flex justify-content-between align-items-start jobs-job-heading">
							<div>
								<h3 class="h4">' . $job['title'] . '</h3>
								<p>' . $job['location']['city'] . ' - ' . $job['type_of_employment'] . ' - ' . $job['id'] . '</p>
							</div>
							<div class="flex align-items-center jobs-job-icons">
								<a target="_blank" href="/download-job.php?identifier=' . $job['id'] . '"><img src="' . get_template_directory_uri() . '/assets/images/download.svg" alt=""></a>
								<a class="mail-job" href="mailto:?body=' . $emailBody . '"><img src="' . get_template_directory_uri() . '/assets/images/mail.svg" alt=""></a>
							</div>
						</div>
						<p>JOB DESCRIPTION<br>' . $job['job_description']['text'] . '</p>
						<p>Qualifications<br>' . $job['qualifications']['text'] . '</p>
					</div>
					<div class="col-md-4">
						<div class="text-center text-md-left">
							<a target="_blank" href="' . $job['apply_url'] . '" class="button dark jobs-job-apply-now"><span>' . __('Apply Now') . '</span></a>
						</div>
						<p>' . $job['company_description']['text'] . '</p>
					</div>
				</div>
			</div>';
        }

        ksort($areaOfExpertise);
        $areaOfExpertiseList = '';
        foreach ($areaOfExpertise as $area) {
            $areaOfExpertiseList .= '<option>' . $area . '</option>';
        }

        ksort($typesOfEmployment);
        $typesOfEmploymentList = '';
        foreach ($typesOfEmployment as $type) {
            $typesOfEmploymentList .= '<option>' . $type . '</option>';
        }

        ksort($professionalExperience);
        $professionalExperienceList = '';
        foreach ($professionalExperience as $experience) {
            $professionalExperienceList .= '<option>' . $experience . '</option>';
        }

        $wrapper_class = make_section_classes($atts, 'jobs-vacancies');
        $defaultLocation = (empty($atts['job_search_location']) || 'none' === $atts['job_search_location'])
            ? '' : ' data-default-location="' . $atts['job_search_location'] . '"';

        $html = '
			<section class="' . $wrapper_class . '"' . $defaultLocation . '>
				<div class="container">
					<div class="row filter-list">
						<div class="col-md-3 order-last order-md-first">
							<span style="display: block; width: 100%; height: 56px; padding: 16px 48px 16px 16px; border: 0; border-radius: 4px; background: #c5c5c5; color: #000; -moz-appearance: none; -webkit-appearance: none;"><span data-job-count>' . count($allJobs) . '</span> Jobs Found</span>
						</div>
						<div class="col-md-3">
							<div class="dropdown-wrapper">
								<select data-filter="area">
									<option value="">Area of Experience</option>
									' . $areaOfExpertiseList . '
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="dropdown-wrapper">
								<select data-filter="type">
									<option value="">Type of Contract</option>
									' . $typesOfEmploymentList . '
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="dropdown-wrapper">
								<select data-filter="experience">
									<option value="">Professional Experience</option>
									' . $professionalExperienceList . '
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="jobs-shortlist">
								<ul>
									' . $items . '
								</ul>
							</div>
						</div>

						<div class="col-md-9 d-md-block ctx-mobile-listing">
							' . $listings . '
						</div>
					</div>
				</div>
			</section>';

        return $html;
    }
}

new ubisoft_job_search();
