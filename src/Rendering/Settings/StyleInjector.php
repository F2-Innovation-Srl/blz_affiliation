<?php

namespace BLZ_AFFILIATION\Rendering\Settings;

use BLZ_AFFILIATION\AdminUserInterface\Settings\PostTypes;

/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class StyleInjector {

    private $style;
    private $amp_style;

    function __construct() {
        
        /// prende le regole scritte nelle options
        $settings = get_option( "blz-affiliation-settings-css" );

        $this->style     = isset( $settings[ 'css_custom_style'     ] ) ? $settings[ 'css_custom_style'     ] : '';
        $this->amp_style = isset( $settings[ 'css_amp_custom_style' ] ) ? $settings[ 'css_amp_custom_style' ] : '';
        
        /// usa wp_enqueue_style ( quindi l'hook corretto è wp_enqueue_scripts )
        add_action( 'wp_enqueue_scripts', [ $this, 'injectExternalStyles' ] );
        
        /// inserisce gli stili in wp_head
        add_action( 'wp_head', [ $this, 'injectCustomStyles' ] );
        
	}

    function injectExternalStyles() {

        # enqueue CSS
        wp_enqueue_style( 'blz-affiliation-grid-css',  BLZ_AFFILIATION_URI ."src/assets/css/flex-grid-lite.css", [], BLZ_AFFILIATION_VERSION, 'all' );
        wp_enqueue_style( 'blz-affiliation-table-css', BLZ_AFFILIATION_URI ."src/assets/css/table-rating.css",   [], BLZ_AFFILIATION_VERSION, 'all' );
    }


	function injectCustomStyles() { 
        
        /// set css rules for amp pages
        if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {

            add_filter( 'amp_css_custom_filter', [ $this, 'injectAmpCSS'] );

            return;
        }
        
        echo '<style>' . $this->style . '</style>';
    }


    public function injectAmpCSS( $css ) {

        $ampStyle = strip_tags( $this->amp_style );

        return $css . $ampStyle;
    }

}