<?php
namespace BLZ_AFFILIATION\Rendering;

/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class StyleInjector {

    private $custom_style;

	function __construct() {
        
        $settings = get_option( "blz-affiliation-settings-css" );

        $this->custom_style = (isset($settings)) ? "<style>".$settings['css_custom_style']."</style>" : "";
        
        add_action( 'init', [ $this, 'init' ] );
        
	}

	function init() { 
        
        if ( !is_admin() ) {
            
            add_action( 'wp_head', [ $this, 'inject_css' ] );        
                        
        }
    }

    function inject_css() { 

        echo $this->custom_style;
    }

}