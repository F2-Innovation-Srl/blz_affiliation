<?php
namespace BLZ_AFFILIATION\Rendering;

/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class StyleInjector {

    private $primary;
    
    private $style = <<<HTML
        <style>
            a.affiliation-intext {
                font-family: inherit !important;
                font-size: inherit !important;
                color: inherit !important;
                text-decoration: underline !important;
                text-decoration-color: # primary # !important;
            }

            a.affiliation-intext:hover {
                color: # primary # !important;
            }
        </style>
    HTML;

	function __construct() {
        
        $settings = get_option( "blz-affiliation-settings" );

        //print_r( $settings ) ; die();
        if( ! isset( $settings[ 'ga_code' ] ) ) return;
                
        add_action( 'init', [ $this, 'init' ] );
        
	}

	function init() { 
        
        if ( !is_admin() ) {
            
            add_action( 'wp_enqueue_styles', [ $this, 'inject_css' ] );        
                        
        }
    }

    function inject_css() { 

        $primary = isset($primary) ? $primary : 'inherit';

        $css = str_replace( ['# primary #'],[$primary], $this->style );

        echo $css;
    }

    
}