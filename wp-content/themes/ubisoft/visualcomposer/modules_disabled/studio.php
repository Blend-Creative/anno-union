<?php
/**
 * VC Module: Headline
 */

class ubisoft_studio extends WPBakeryShortCode
{

    /**
     * Element init.
     */
    function __construct()
    {
        add_action('wp_loaded', array($this, 'element_mapping'));
        add_shortcode('ubisoft_studio', array($this, 'element_frontend'));
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
                'name' => _x('Our Studios', 'visualcomposer'),
                'description' => _x('Creates an "Our Studios" section".', 'visualcomposer'),
                'category' => _x('Content', 'visualcomposer'),
                'base' => 'ubisoft_studio',
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Title', 'visualcomposer'),
                        'param_name' => 'title',
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => _x('Text', 'visualcomposer'),
                        'param_name' => 'copy',
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
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Berlin Image', 'visualcomposer'),
                        'param_name' => 'image_id_berlin',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Düsseldorf Image', 'visualcomposer'),
                        'param_name' => 'image_id_duesseldorf',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => _x('Mainz Image', 'visualcomposer'),
                        'param_name' => 'image_id_mainz',
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
			<h3>Our Studios</h3>
			{{ params.title }}<br />
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
        $title = '';
        $copy = '';

        if (!is_array($atts)) {
            $atts = [];
        }

        if (isset($atts['title']) && $atts['title'] !== '') {
            $title = $atts['title'];
        } else {
            // Fallback to default.
            $title = ICL_LANGUAGE_CODE == 'en' ? 'One studio network, three unique Ubisoft studios.' : 'Ein Studio-Netzwerk, drei einzigartige Ubisoft Studios.';
        }
        if (isset($atts['copy']) && $atts['copy'] !== '') {
            $copy = $atts['copy'];
        } else {
            // Fallback to default.
            $copy = ICL_LANGUAGE_CODE == 'en' ? 'Ubisoft Blue Byte is the renowned network of three development studios operating out of Germany. Each studio contributes to Ubisoft’s global success through excellent AAA projects.' : 'Ubisoft Blue Byte ist das renommierte Studio-Netzwerk bestehend aus drei Entwicklerstudios aus Deutschlands. Jedes Studio trägt durch ausgezeichnete AAA-Projekte zu Ubisofts weltweitem Erfolg bei.';
        }


        $col1 = 'col-md-8';
        $col2 = 'col-md-4';
        $extra = 'style="padding-right: 20px; margin-bottom: 40px;"';

        $wrapper_class = make_section_classes($atts, 'homepage-studios');

        if (!isset($atts['image_id_berlin'])) {
          $atts['image_id_berlin'] = get_template_directory_uri() . '/assets/images/berlin-square.jpg';
        } else {
          $atts['image_id_berlin'] = wp_get_attachment_image_url($atts['image_id_berlin'], 'xxl');
        }
        if (!isset($atts['image_id_duesseldorf'])) {
          $atts['image_id_duesseldorf'] = get_template_directory_uri() . '/assets/images/dusseldorf-square.jpg';
        } else {
          $atts['image_id_duesseldorf'] = wp_get_attachment_image_url($atts['image_id_duesseldorf'], 'xxl');
        }
        if (!isset($atts['image_id_mainz'])) {
          $atts['image_id_mainz'] = get_template_directory_uri() . '/assets/images/mainz-square.jpg';
        } else {
          $atts['image_id_mainz'] = wp_get_attachment_image_url($atts['image_id_mainz'], 'xxl');
        }

        $html = '
    <section class="' . $wrapper_class . '">
      <div class="container">
        <div class="row align-items-center">
          <div class="' . $col1 . '" ' . $extra . '>
            <h2 class="h2">' . $title . '</h2>
          </div>
          <div class="' . $col2 . '">
            <p>' . $copy . '</p>
          </div>
        </div>
        <div class="row relative studio-selection">
          <div class="col-md-12">
            <div class="row">
              <div class="homepage-studio-overlay" data-overlay="berlin">
                <div class="homepage-studio-overlay-map">
                  <div id="berlinMap" class="berlinMap" style="width: 100%; height: 100%"></div>
                  <script type="text/javascript">
                    var map1;
                    var map2;
                    var map3;

                    function initialize() {
                      // BERLIN
                      map1 = new google.maps.Map(document.getElementById("berlinMap"), {
                        center: {lat: 52.508385, lng: 13.329194},
                        zoom: 15,
                        styles: [
                          {
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#f5f5f5"
                              }
                            ]
                          },
                          {
                            "elementType": "labels.icon",
                            "stylers": [
                              {
                                "visibility": "off"
                              }
                            ]
                          },
                          {
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#616161"
                              }
                            ]
                          },
                          {
                            "elementType": "labels.text.stroke",
                            "stylers": [
                              {
                                "color": "#f5f5f5"
                              }
                            ]
                          },
                          {
                            "featureType": "administrative.land_parcel",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#bdbdbd"
                              }
                            ]
                          },
                          {
                            "featureType": "poi",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#eeeeee"
                              }
                            ]
                          },
                          {
                            "featureType": "poi",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#757575"
                              }
                            ]
                          },
                          {
                            "featureType": "poi.park",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#e5e5e5"
                              }
                            ]
                          },
                          {
                            "featureType": "poi.park",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#9e9e9e"
                              }
                            ]
                          },
                          {
                            "featureType": "road",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#ffffff"
                              }
                            ]
                          },
                          {
                            "featureType": "road.arterial",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#757575"
                              }
                            ]
                          },
                          {
                            "featureType": "road.highway",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#dadada"
                              }
                            ]
                          },
                          {
                            "featureType": "road.highway",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#616161"
                              }
                            ]
                          },
                          {
                            "featureType": "road.local",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#9e9e9e"
                              }
                            ]
                          },
                          {
                            "featureType": "transit.line",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#e5e5e5"
                              }
                            ]
                          },
                          {
                            "featureType": "transit.station",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#eeeeee"
                              }
                            ]
                          },
                          {
                            "featureType": "water",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#c9c9c9"
                              }
                            ]
                          },
                          {
                            "featureType": "water",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#9e9e9e"
                              }
                            ]
                          }
                        ]
                      });

                      // DUESSELDORF
                      map2 = new google.maps.Map(document.getElementById("duesseldorfMap"), {
                        center: {lat: 51.232863, lng: 6.819600},
                        zoom: 15,
                        styles: [
                          {
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#f5f5f5"
                              }
                            ]
                          },
                          {
                            "elementType": "labels.icon",
                            "stylers": [
                              {
                                "visibility": "off"
                              }
                            ]
                          },
                          {
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#616161"
                              }
                            ]
                          },
                          {
                            "elementType": "labels.text.stroke",
                            "stylers": [
                              {
                                "color": "#f5f5f5"
                              }
                            ]
                          },
                          {
                            "featureType": "administrative.land_parcel",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#bdbdbd"
                              }
                            ]
                          },
                          {
                            "featureType": "poi",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#eeeeee"
                              }
                            ]
                          },
                          {
                            "featureType": "poi",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#757575"
                              }
                            ]
                          },
                          {
                            "featureType": "poi.park",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#e5e5e5"
                              }
                            ]
                          },
                          {
                            "featureType": "poi.park",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#9e9e9e"
                              }
                            ]
                          },
                          {
                            "featureType": "road",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#ffffff"
                              }
                            ]
                          },
                          {
                            "featureType": "road.arterial",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#757575"
                              }
                            ]
                          },
                          {
                            "featureType": "road.highway",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#dadada"
                              }
                            ]
                          },
                          {
                            "featureType": "road.highway",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#616161"
                              }
                            ]
                          },
                          {
                            "featureType": "road.local",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#9e9e9e"
                              }
                            ]
                          },
                          {
                            "featureType": "transit.line",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#e5e5e5"
                              }
                            ]
                          },
                          {
                            "featureType": "transit.station",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#eeeeee"
                              }
                            ]
                          },
                          {
                            "featureType": "water",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#c9c9c9"
                              }
                            ]
                          },
                          {
                            "featureType": "water",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#9e9e9e"
                              }
                            ]
                          }
                        ]
                      });

                      // MAINZ
                      map3 = new google.maps.Map(document.getElementById("mainzMap"), {
                        center: {lat: 50.001461, lng: 8.268573},
                        zoom: 15,
                        styles: [
                          {
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#f5f5f5"
                              }
                            ]
                          },
                          {
                            "elementType": "labels.icon",
                            "stylers": [
                              {
                                "visibility": "off"
                              }
                            ]
                          },
                          {
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#616161"
                              }
                            ]
                          },
                          {
                            "elementType": "labels.text.stroke",
                            "stylers": [
                              {
                                "color": "#f5f5f5"
                              }
                            ]
                          },
                          {
                            "featureType": "administrative.land_parcel",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#bdbdbd"
                              }
                            ]
                          },
                          {
                            "featureType": "poi",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#eeeeee"
                              }
                            ]
                          },
                          {
                            "featureType": "poi",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#757575"
                              }
                            ]
                          },
                          {
                            "featureType": "poi.park",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#e5e5e5"
                              }
                            ]
                          },
                          {
                            "featureType": "poi.park",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#9e9e9e"
                              }
                            ]
                          },
                          {
                            "featureType": "road",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#ffffff"
                              }
                            ]
                          },
                          {
                            "featureType": "road.arterial",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#757575"
                              }
                            ]
                          },
                          {
                            "featureType": "road.highway",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#dadada"
                              }
                            ]
                          },
                          {
                            "featureType": "road.highway",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#616161"
                              }
                            ]
                          },
                          {
                            "featureType": "road.local",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#9e9e9e"
                              }
                            ]
                          },
                          {
                            "featureType": "transit.line",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#e5e5e5"
                              }
                            ]
                          },
                          {
                            "featureType": "transit.station",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#eeeeee"
                              }
                            ]
                          },
                          {
                            "featureType": "water",
                            "elementType": "geometry",
                            "stylers": [
                              {
                                "color": "#c9c9c9"
                              }
                            ]
                          },
                          {
                            "featureType": "water",
                            "elementType": "labels.text.fill",
                            "stylers": [
                              {
                                "color": "#9e9e9e"
                              }
                            ]
                          }
                        ]
                      });

                      // Search for Ubisoft Berlin
                      var request = {
                        location: map1.getCenter(),
                        radius: "500",
                        query: "Ubisoft Berlin"
                      };
                  
                      var service = new google.maps.places.PlacesService(map1);
                      service.textSearch(request, callback1);

                      // Search for Ubisoft Düsseldorf
                      var request = {
                        location: map2.getCenter(),
                        radius: "500",
                        query: "Ubisoft Düsseldorf"
                      };
                  
                      var service = new google.maps.places.PlacesService(map2);
                      service.textSearch(request, callback2);

                      // Search for Ubisoft Mainz
                      var request = {
                        location: map3.getCenter(),
                        radius: "500",
                        query: "Ubisoft Mainz"
                      };
                  
                      var service = new google.maps.places.PlacesService(map3);
                      service.textSearch(request, callback3);
                    }
                    
                    // Checks that the PlacesServiceStatus is OK, and adds a marker
                    // using the place ID and location from the PlacesService.
                    function callback1(results, status) {
                      if (status == google.maps.places.PlacesServiceStatus.OK) {
                        var marker = new google.maps.Marker({
                          map: map1,
                          place: {
                            placeId: results[0].place_id,
                            location: results[0].geometry.location
                          },
                          label: "U",
                          title: "Ubisoft Berlin"
                        });
                      }
                    }

                    function callback2(results, status) {
                      if (status == google.maps.places.PlacesServiceStatus.OK) {
                        var marker = new google.maps.Marker({
                          map: map2,
                          place: {
                            placeId: results[0].place_id,
                            location: results[0].geometry.location
                          },
                          label: "U",
                          title: "Ubisoft Düsseldorf"
                        });
                      }
                    }

                    function callback3(results, status) {
                      if (status == google.maps.places.PlacesServiceStatus.OK) {
                        var marker = new google.maps.Marker({
                          map: map3,
                          place: {
                            placeId: results[0].place_id,
                            location: results[0].geometry.location
                          },
                          label: "U",
                          title: "Ubisoft Mainz"
                        });
                      }
                    }
                    
                    google.maps.event.addDomListener(window, "load", initialize);
                  </script>
                </div>
                <div class="homepage-studio-overlay-content berlin">
                  <button class="homepage-studio-overlay-close js-close-studio-overlay"></button>
                  <h2 class="h3">Ubisoft Berlin</h2>
                  <p>
                    Hardenbergstraße 32,<br />
                    10623 Berlin<br />
                    T +49 (0) 30 726 212 90<br />
                    E <a href="mailto:berlin@ubisoft.com">berlin@ubisoft.com</a>
                  </p>
                </div>
              </div>

              <div class="homepage-studio-overlay" data-overlay="dusseldorf">
                <div class="homepage-studio-overlay-map">
                  <div id="duesseldorfMap" class="duesseldorfMap" style="width: 100%; height: 100%"></div>
                </div>
                <div class="homepage-studio-overlay-content dusseldorf">
                  <button class="homepage-studio-overlay-close js-close-studio-overlay"></button>
                  <h2 class="h3">Ubisoft Düsseldorf</h2>
                  <p>
                    Luise-Rainer-Straße 7,<br/>
                    40235 Düsseldorf<br/>
                    T +49 (0) 211 54 08 9580<br/>
                    E <a href="mailto:duesseldorf@ubisoft.com">duesseldorf@ubisoft.com</a>
                  </p>
                </div>
              </div>

              <div class="homepage-studio-overlay" data-overlay="mainz">
                <div class="homepage-studio-overlay-map">
                  <div id="mainzMap" class="mainzMap" style="width: 100%; height: 100%"></div>
                </div>
                <div class="homepage-studio-overlay-content mainz">
                  <button class="homepage-studio-overlay-close js-close-studio-overlay"></button>
                  <h2 class="h3">Ubisoft Mainz</h2>
                  <p>
                    Adolf-Kolping-Straße 4,<br />
                    55116 Mainz<br />
                    T +49 (0) 613 155 44 70<br />
                    E <a href="mailto:mainz@ubisoft.com">mainz@ubisoft.com</a>
                  </p>
                </div>
              </div>

              <div class="col-md-4">
                <a href="#" class="homepage-studio js-open-studio-overlay" data-studio="berlin">
                  <img src="' . $atts['image_id_berlin'] . '" alt="Ubisoft Berlin" class="img-shadow" />
                  <div><h3 class="h3">Berlin</h3></div>
                </a>
              </div>

              <div class="col-md-4">
                <a href="#" class="homepage-studio js-open-studio-overlay" data-studio="dusseldorf">
                  <img src="' . $atts['image_id_duesseldorf'] . '" alt="Ubisoft Düsseldorf" class="img-shadow" />
                  <div><h3 class="h3">Düsseldorf</h3></div>
                </a>
              </div>

              <div class="col-md-4">
                <a href="#" class="homepage-studio js-open-studio-overlay" data-studio="mainz">
                  <img src="' . $atts['image_id_mainz'] . '" alt="Ubisoft Mainz" class="img-shadow" />
                  <div><h3 class="h3">Mainz</h3></div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    ';

        return $html;
    }

}

new ubisoft_studio();
