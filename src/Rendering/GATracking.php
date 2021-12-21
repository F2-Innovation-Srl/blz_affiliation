<?php
namespace BLZ_AFFILIATION\Rendering;

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
    private $ga_code;
    
    private $analitics_track =   <<<HTML
       
        <amp-analytics type="gtag" data-credentials="include">
            <script type="application/json">
                {
                    "vars" : {
                        "account": "{GA_TRACKING_ID}",
                        "gtag_id": "{GA_TRACKING_ID}",
                        "config" : {
                            "{GA_TRACKING_ID}": { "groups": "default" }
                        }
                    },
                    "triggers": {
                        "affiliateLinkView": {
                            "on": "visible",
                            "request": "affiliateelementsview",
                            "selector": [ "[data-vars-affiliate]" ],
                            "vars": {
                                "event_name": "{dollaro}{affiliate}",
                                "event_action": "{dollaro}{affiliate} view",
                                "event_category": "affiliate-tracking",
                                "method": "Google"
                            }
                        },
                        "affiliateLinkClick": {
                            "selector": "[data-vars-affiliate]",
                            "on": "click",
                            "vars": {	
                                "event_name": "{dollaro}{affiliate}",
                                "event_action": "{dollaro}{affiliate} click",
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
       
        if( isset( $settings['ga_code'] ) ){

            $this->ga_code = $settings['ga_code'];
        
            add_action( 'init', [ $this, 'onInit' ] );
        }
	}

	function onInit() { 
        if ( !is_admin() ) {
            
            //aggiunge variabile GA in header
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_js' ] );            
            
            //aggiunge analitics su pagine AMP
            add_filter( 'the_content',  [ $this, 'add_amp_track'], 20 );
        }
    }

    function enqueue_js() { 
        ?>
        <script>
            // custom blz_affiliation JS code
            var blz_affiliation_ga = "<?php echo $this->ga_code;?>";
        </script>
        <?php
    }
    /**
     * Add a icon to the beginning of every post page.
     *
     * @uses is_single()
     */
    function add_amp_track( $content ) {
    
        if ( is_single() && is_amp_endpoint())
            // Add image to the beginning of each page
            $content .= str_replace(['{dollaro}','{GA_TRACKING_ID}'],['$',$this->ga_code],$this->analitics_track);
    
        // Returns the content.
        return $content;
    }
}