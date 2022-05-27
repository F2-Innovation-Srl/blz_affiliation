<?php
namespace BLZ_AFFILIATION\Rendering;
use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Rendering\Settings\SettingsData;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Config;
/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class Disclamer {

    private $text;

	public function __construct() {

        // Add the custom columns to the posts post type:
        add_filter( 'the_content', [ $this, 'add'], 99 );        
    }

	function add($content) { 

        $config = Config::loadSettings();
        $this->text = "";

        //1) se esiste un link affialito in pagina mi prendo il disclamer generale
        if ($config->is_affiliation_page == "true"){
            $text = get_option( "blz-affiliation-settings-disclamer" );
            $this->text = (!empty($text["disclamer"])) ? "<p class='blz_affiliation_disclamer'>" . $text["disclamer"] . "</p>" : "";
        }

        ///2) Verifico se c'è una regola impostata da attivatore e in caso mi prendo il disclamer impostato ad-hoc
        $request = new Request([]);
        /// inizializzo i settingsData 
        $SettingsData = new SettingsData("blz_disclamer",$request);
        if (!empty($SettingsData->getGAEvent())) $this->text = $SettingsData->getGAEvent();
        
        return $content. "<p class='blz_affiliation_disclamer'>" .$this->text."</p>";
    }


}