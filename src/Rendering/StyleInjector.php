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
        
        $settings = get_option( "blz-affiliation-settings-css" );

        $this->primary = $settings['primary_color'] ? $settings['primary_color'] : 'inherit';
        
        add_action( 'init', [ $this, 'init' ] );
        
	}

	function init() { 
        
        if ( !is_admin() ) {
            
            add_action( 'wp_enqueue_styles', [ $this, 'inject_css' ] );        
                        
        }
    }

    function inject_css() { 


        $css = str_replace( ['# primary #'],[$this->primary], $this->style );

        echo $css;
    }

    
}