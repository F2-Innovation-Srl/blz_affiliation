<?php
namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Config;
/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class Disclamer {

    private $text;

	public function __construct() {

        $text = get_option( "blz-affiliation-settings-disclamer" );
        
        $this->text = (!empty($text["disclamer"])) ? $text["disclamer"] : "<p class='blz_affiliation_disclamer'>Disclamer</p>";
        // Add the custom columns to the posts post type:
        add_filter( 'the_content', [ $this, 'add'], 99 );        
    }

	function add($content) { 
        
        $config = Config::loadSettings();
        if ($config->is_affiliation_page == "true")
            return $content . $this->text;
        else    
            return $content;
    }


}