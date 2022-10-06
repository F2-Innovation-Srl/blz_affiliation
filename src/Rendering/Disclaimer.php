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
        <p id="disclaimer{{ rand }}" class='blz_affiliation_disclamer'></p>
        <script>
            document.getElementById('disclaimer{{ rand }}').textContent = '{{ text }}';
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

            $text_option = get_option( "blz-affiliation-settings-disclamer" );
            
            $text = !empty( $text_option["disclamer"] ) ? $text_option["disclamer"] : "";
        }

        /// 2) se c'è una regola impostata da attivatore prende il disclamer impostato ad-hoc
        $request = new Request([]);
        
        /// inizializza i settingsData 
        $SettingsData = new SettingsData( "blz_disclamer", $request );

        if ( !empty( $SettingsData->getGAEvent() ) ) {

            $text = $SettingsData->getGAEvent();
        }

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