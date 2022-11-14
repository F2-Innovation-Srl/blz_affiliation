<?php

namespace BLZ_AFFILIATION\Rendering\Settings;

use BLZ_AFFILIATION\Utils\Helper;
/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class ScriptInjector {

    function __construct() {
        
        if( !Helper::isTrackerEnabled() ) return;
            
        add_action( 'wp_enqueue_scripts', [ $this, 'injectTrackingScripts' ] );        
	}

	function injectTrackingScripts() { 
        
        /// dipendenze js per tracciamento
        wp_enqueue_script( 'blz-affiliation-tracker',   BLZ_AFFILIATION_URI . 'src/assets/js/libs/blz_tr.js',             [],                          BLZ_AFFILIATION_VERSION, true );
        wp_enqueue_script( 'blz-affiliation-activator', BLZ_AFFILIATION_URI . 'src/assets/js/affiliate-link-activator.js',["blz-affiliation-tracker"], BLZ_AFFILIATION_VERSION, true );        
    }

}