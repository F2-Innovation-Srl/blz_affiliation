<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/**
 * Class AdminPage
 *
 * @package BLZ_AFFILIATION
 */
class AdminPage {
    public $page = "blz-affiliation";
	/**
	 * AdminPage constructor.
	 */
	function __construct() {
        
        # set admin actions callback
        add_action('admin_menu', [ $this, 'adminMenu' ]);
        add_action('init', [ $this, 'custom_enqueue' ]);
	}

	/**
     * Invoked on admin_menu action
     * Create admin menu 
    **/
	public function adminMenu() {

        $gaTrakingIdSettings = new GaTrakingIdSettings();
        $programLinksOptions = new ProgramLinksOptions();
        add_menu_page('Blazemedia Affilitation', 'Blazemedia Affilitation', 'manage_options', $this->page);
        add_submenu_page($this->page,'GA e TrackingID Settings', 'GA e TrackingID Settings', 'manage_options', $this->page, [ $gaTrakingIdSettings, 'render']);
        add_submenu_page($this->page,'Program Links Options', 'Program Links Options', 'manage_options', $this->page, [ $programLinksOptions, 'render']);

	}

    public static function custom_enqueue()
    {
        /*
        if (! is_admin()) {
            # enqueue scripts
            wp_enqueue_script(
                'blz-affiliation-javascripts',
                plugins_url("assets/js/tracker.js", dirname(__FILE__)),
                [],
                PLUGIN_VERSION,
                true
            );
        }
        */
    }
    
}