<?php

namespace BLZ_AFFILIATION\Rendering\Settings;

use BLZ_AFFILIATION\Utils\Helper;
/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class ScriptInjector {

    private $tracker_enabled;

	function __construct() {
        
        $this->tracker_enabled = helper::isTrackerEnabled();
        
        add_action( 'init', [ $this, 'init' ] );
        
	}

	function init() { 
        
        if (is_admin()) {
            # enqueue scripts
            wp_enqueue_script(
                'blz-affiliation-adminjs',
                BLZ_AFFILIATION_URI ."src/assets/js/admin.js",
                [],
                BLZ_AFFILIATION_VERSION,
                true
            );
        } else {
           
            if ($tracker_enabled) {
                /// dipendenze js per tracciamento
                wp_enqueue_script('blz-affiliation-tracker',   BLZ_AFFILIATION_URI ."src/assets/js/libs/blz_tr.js",[], BLZ_AFFILIATION_VERSION,true);
                wp_enqueue_script('blz-affiliation-activator', BLZ_AFFILIATION_URI ."src/assets/js/affiliate-link-activator.js",["blz-affiliation-tracker"], BLZ_AFFILIATION_VERSION,true);
            }
             
        }
    }

}