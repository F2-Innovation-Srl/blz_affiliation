<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class AdminScriptInjector {

    private $tracker_enabled;

	function __construct() {
        
        add_action( 'init', [ $this, 'injectAdminScript' ] );
        
	}

	function injectAdminScript() { 
        
        # enqueue scripts
        wp_enqueue_script(
            'blz-affiliation-adminjs',
            BLZ_AFFILIATION_URI ."src/assets/js/admin.js",
            [],
            BLZ_AFFILIATION_VERSION,
            true
        );
    }
}