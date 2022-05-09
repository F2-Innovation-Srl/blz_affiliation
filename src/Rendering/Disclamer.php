<?php
namespace BLZ_AFFILIATION\Rendering;

/**
 * Aggiunge CSS che usa parametri presi dai settings
 * 
 */
class Disclamer {

    private $text;

	public function __construct() {

        $text = get_option( "blz-affiliation-settings-disclamer" );

        $this->text = (isset($text["disclamer"])) ? "<style>".$text["disclamer"]."</style>" : "<p class='blz_affiliation_disclamer'>Disclamer</p>";

        // Add the custom columns to the posts post type:
        add_filter( 'the_content', [ $this, 'add'] );        
    }

	function add() { 
        
        $config = Config::loadSettings();
        if ($config->is_affiliation_page)
            return string $content + $this->text ;
        
    }


}