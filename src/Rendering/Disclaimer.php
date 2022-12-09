<?php
namespace BLZ_AFFILIATION\Rendering;
use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Rendering\Settings\SettingsData;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Config;


/**
 * Scrive il disclaimer in pagina prendendolo dai settings
 */
class Disclaimer {

    private $text = <<<HTML
        <p id="disclaimer{{ rand }}" class='blz_affiliation_disclaimer'>{{ text }}</p>
    HTML;

    private $text_js = <<<HTML
        <p id="disclaimer{{ rand }}" class='blz_affiliation_disclaimer'></p>
        <script>
            document.addEventListener('DOMContentLoaded', (evt) => {
                let blz_disclaimer = document.getElementById('disclaimer{{ rand }}');
                let tmp = blz_disclaimer.cloneNode();
                let container = blz_disclaimer.parentElement;
                blz_disclaimer.remove();
                
                tmp.textContent = '{{ text }}';
                container.appendChild( tmp );        
            });
        </script>
    HTML;


	public function __construct() {

        // Add the custom columns to the posts post type:
        add_filter( 'the_content', [ $this, 'add'], 99 );        
    }


	function add( $content ) { 

        $config = Config::loadSettings();

        $text = '';

        /// 1) se esiste un affiliation link in pagina prende il disclaimer generale
        if( $config->is_affiliation_page == "true" ) {

            $text_option = get_option( "blz-affiliation-settings-disclaimer" );
            
            $text = !empty( $text_option["disclaimer"] ) ? $text_option["disclaimer"] : "";
        }

        /// 2) se c'è una regola impostata da attivatore prende il disclaimer impostato ad-hoc
        $request = new Request([]);
        
        $settingsData = new SettingsData( "blz_disclaimer", $request );
        if ( !empty( $settingsData->getGAEvent() ) )  $text = $settingsData->getGAEvent();

        $disclaimer = empty( $text ) ? '' : str_replace(
            [ '{{ text }}', '{{ rand }}' ],
            [ $text, $this->randomID() ],
            $this->text
        );


        return $content . $disclaimer;
    }


    /**
     * return a random $pad-digits string
     *
     * @return string
     */
    private function randomID( $pad = 10 ){

        return implode( '', array_map( function( ) { return chr( rand( 65, 85 ) ); }, range( 0, $pad ) ));
    }

}