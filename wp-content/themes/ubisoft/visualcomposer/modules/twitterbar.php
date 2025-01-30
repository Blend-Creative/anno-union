<?php
if( class_exists( 'WPBakeryShortCode' ) && !class_exists( 'anno_twitterbar' ) ) {

	class anno_twitterbar extends WPBakeryShortCode {

		function __construct() {

			// this function is for UI
			add_action( 'init', array( $this, 'anno_twitterbar_mapping' ) );

			// shortcode name should match the base parameter of vc_map() function
			add_shortcode( 'anno_twitterbar', array( $this, 'anno_twitterbar_widget' ) );
		}

		// Element UI
		public function anno_twitterbar_mapping() {

			// Do nothing if VComposer is not enabled, we've already checked this but better safe than sorry :D
			if ( !defined( 'WPB_VC_VERSION' ) ) {
				return;
			}

			// parameters of the block
			vc_map([
				'name' => _x('Twitterbar', 'visualcomposer', THEME_TEXTDOMAIN),
				'base' => 'anno_twitterbar', // shortcode name as well
				'description' => 'Creates the twitterbar.',
				'category' => __('Content'), // you can use any custom category name
				'icon' => 'icon-wpb-wp', // we can set here the full URL of the image as well
				'params' => [
					[
						'type' => 'dropdown',
						'heading' => _x( 'Channel', 'visualcomposer', THEME_TEXTDOMAIN ),
						'param_name' => 'channel',
						'description' => _x( 'Select a channel for this twitterbar.', 'visualcomposer', THEME_TEXTDOMAIN ),
						
						'admin_label' => true,
						'group' => __( 'General', 'js_composer' ),
					],
				]
			]);
		}

		// HTML of our widget
		public function anno_twitterbar_widget( $atts ) {

			// Let's compare parameters with defaults
			$atts = shortcode_atts( [
				'channel' => 'ubisoft',
//				'type' => 'masonry'
			], $atts);

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $atts['channel'] . "&count=4",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					"authorization: Bearer " . "AAAAAAAAAAAAAAAAAAAAAP%2BzAwEAAAAA6n6LB70lVVDJDxScN0cAQDDuyTM%3DBEqYgxYv0pUf4FQasyMLCZCQ0YyNp8RjQjjheHDwEb2eyAtfPS",
					"cache-control: no-cache"
				),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				$arrResponse = json_decode($response, true);
				array_walk($arrResponse, function(&$post) {
					preg_match("/(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/im", $post['text'], $arrLinks);
					$post['text'] = preg_replace('/(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/im', '', $post['text']);
					$post['created_at'] = \DateTime::createFromFormat('D M d H:i:s O Y', $post['created_at'], new \DateTimeZone('UTC'));
                    $post['extracted_links'] = $arrLinks;
				});
                $atts['posts'] = $arrResponse;
                set_query_var( 'tweets', $arrResponse );

                $html = get_template_part( 'template-parts/twitterbar', null, $atts );
				return $html;
			}
		}
	}

	new anno_twitterbar();
}
