<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/**
 * Class Page
 *
 * @package BLZ_AFFILIATION
 */
class Page {
	/**
	 * Page constructor.
	 */
	function __construct() {
        
        add_action('init', [ $this, 'custom_enqueue' ]);
       
	}

	function custom_enqueue() { 
        add_action( 'wp_enqueue_scripts', [ $this, 'mytheme_enqueue_typekit' ] );
        
    }
    function mytheme_enqueue_typekit() { 
         wp_add_inline_script( 'mytheme-typekit', 'var blz-affiliation-ga=\'GA_sssss\'' );
    }
}