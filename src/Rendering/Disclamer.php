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

        //$text = get_option( "blz-affiliation-settings-disclamer" );
        /// prende la request
        $request = new Request([]);
        /// inizializzo i settingsData 
        $SettingsData = new SettingsData("blz_disclamer",$request);
          
        $this->text = $SettingsData->getGAEvent();
        // Add the custom columns to the posts post type:
        add_filter( 'the_content', [ $this, 'add'], 99 );        
    }

	function add($content) { 
        
        $config = Config::loadSettings();
        if ($config->is_affiliation_page == "true")
            return $content. "<p class='blz_affiliation_disclamer'>" .$this->text."</p>";
        else   
            return $content;
        
    }


}