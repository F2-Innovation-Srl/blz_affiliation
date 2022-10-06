<?php

namespace BLZ_AFFILIATION\Rendering\Settings;

/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class StyleInjector {

    private $custom_style;

	function __construct() {
        
        $settings = get_option( "blz-affiliation-settings-css" );

        $this->custom_style = (isset($settings['css_custom_style'])) ? "<style>".$settings['css_custom_style']."</style>" : "";
        $this->custom_amp_style = (isset($settings['css_amp_custom_style'])) ? "<style>".$settings['css_amp_custom_style']."</style>" : "";
        
        add_action( 'init', [ $this, 'init' ] );
        
	}

	function init() { 
        
        if ( !is_admin() ) {
            
            if ( function_exists( 'is_amp_endpoint' ) && @is_amp_endpoint() ) {

                // Add a the css for the rendered links
                add_filter( 'amp_css_custom_filter', [ $this, 'injectAmpCSS'] );

            } else {

                add_action( 'wp_head', [ $this, 'injectCSS' ] );
            }                    
        }
    }

    
    public function injectCss() { 

        echo $this->custom_style;
    }

    public function injectAmpCSS( $css ){

        $customStyle = strip_tags( $this->custom_amp_style );

        return $css .$customStyle;
    }

}