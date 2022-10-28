<?php

namespace BLZ_AFFILIATION\Rendering\Settings;

use BLZ_AFFILIATION\Utils\Helper;
/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class ScriptInjector {

    private $is_tracking_enabled;

	function __construct() {
        
        $this->is_tracking_enabled = helper::isTrackerEnabled();

        if ( $this->is_tracking_enabled ) {
            add_action( 'init', [ $this, 'injectTrackingScripts' ] );
        }
        
	}

	function injectTrackingScripts() { 
        
        /// dipendenze js per tracciamento
        wp_enqueue_script('blz-affiliation-tracker',   BLZ_AFFILIATION_URI ."src/assets/js/libs/blz_tr.js",[], BLZ_AFFILIATION_VERSION,true);
        wp_enqueue_script('blz-affiliation-activator', BLZ_AFFILIATION_URI ."src/assets/js/affiliate-link-activator.js",["blz-affiliation-tracker"], BLZ_AFFILIATION_VERSION,true);        
    }

}