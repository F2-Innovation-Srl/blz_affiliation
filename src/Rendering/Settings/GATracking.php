<?php
namespace BLZ_AFFILIATION\Rendering\Settings;

/**
 * Class GATracking
 * Activate GA Tracking in both normal and AMP pages
 *
 * @package BLZ_AFFILIATION
 */
class GATracking {
	/**
	 * Page constructor.
	 */
    private $ga_code = '';
    
    private $analitics_track =   <<<HTML
       
        <amp-analytics type="gtag" data-credentials="include">
            <script type="application/json">
                {
                    "vars" : {
                        "account": "{GA_TRACKING_ID}",
                        "gtag_id": "{GA_TRACKING_ID}",
                        "config" : {
                            "{GA_TRACKING_ID}": { "groups": "default", "send_page_view" : false }
                        }
                    },
                    "triggers": {
                        "affiliateLinkView": {
                            "on": "visible",
                            "request": "affiliateelementsview",
                            "selector": [ "[data-vars-blz-affiliate]" ],
                            "vars": {
                                "event_name": "{dollaro}{blzAffiliate}",
                                "event_action": "{dollaro}{blzAffiliate} view",
                                "event_category": "affiliate-tracking",
                                "method": "Google"
                            }
                        },
                        "affiliateLinkClick": {
                            "selector": "[data-vars-blz-affiliate]",
                            "on": "click",
                            "vars": {	
                                "event_name": "{dollaro}{blzAffiliate}",
                                "event_action": "{dollaro}{blzAffiliate} click",
                                "event_category": "affiliate-tracking",
                                "method": "Google"
                            }
                        }
                    }
                }
            </script>
        </amp-analytics>

    HTML;


	function __construct() {
        
        $settings = get_option( "blz-affiliation-settings" );
       
        /// verifica che esista una impostazione per ga_code
        if( !isset( $settings['ga_code'] ) || is_admin() ) return ; 
        
        $this->ga_code = $settings['ga_code'];

        add_action( 'init', [ $this, 'onInit' ] );
    }
    
	function onInit() { 

        if ( !is_admin() ) {

            //aggiunge variabile GA in header
            add_action( 'wp_head',  [ $this, 'enqueue_js' ] , 10  );
            
            //aggiunge analitics su pagine AMP
            add_filter( 'the_content',  [ $this, 'add_amp_track'], 20 );       
        }
    }

    function enqueue_js() { 

        if( !is_single() ) return ;

        $script = <<<HTML
            <script>
                var blz_affiliation_ga = "{{ ga_code }}";
            </script>
        HTML;
        
        echo str_replace('{{ ga_code }}', $this->ga_code, $script );
    }

    
    /**
     * Add a icon to the beginning of every post page.
     *
     * @uses is_single()
     */
    function add_amp_track( $content ) {

        $is_amp = function_exists('is_amp_endpoint') ? is_amp_endpoint() : false;
    
        if ( is_single() && $is_amp ) {

            // Add image to the beginning of each page
            $content .= str_replace(['{dollaro}','{GA_TRACKING_ID}'],['$',$this->ga_code],$this->analitics_track);
        }
        // Returns the content.
        return $content;
    }
}