<?php

namespace BLZ_AFFILIATION\Rendering\Settings;

/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class ScriptInjector {

    private $tracking_disable;

	function __construct() {
        
        $settings = get_option( "blz-affiliation-settings-css" );

        $this->tracking_disable = (isset($settings['tracking_disable'])) ? $settings['tracking_disable'] : false;
        
        add_action( 'init', [ $this, 'init' ] );
        
	}

	function init() { 
        
        if (is_admin()) {
            # enqueue scripts
            wp_enqueue_script(
                'blz-affiliation-adminjs',
                PLUGIN_URI ."src/assets/js/admin.js",
                [],
                PLUGIN_VERSION,
                true
            );
        } else {
           
            if (! $tracking_disable) {
                /// dipendenze js per tracciamento
                wp_enqueue_script('blz-affiliation-tracker',   PLUGIN_URI ."src/assets/js/libs/blz_tr.js",[], PLUGIN_VERSION,true);
                wp_enqueue_script('blz-affiliation-activator', PLUGIN_URI ."src/assets/js/affiliate-link-activator.js",["blz-affiliation-tracker"], PLUGIN_VERSION,true);
            }
            # enqueue CSS
            wp_enqueue_style( 'blz-affiliation-grid-css', PLUGIN_URI ."src/assets/css/flex-grid-lite.css", [],  PLUGIN_VERSION, 'all' );
            wp_enqueue_style( 'blz-affiliation-table-css', PLUGIN_URI ."src/assets/css/table-rating.css", [],  PLUGIN_VERSION, 'all' );
            
        }
    }

}