<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/**
 * Class AdminPage
 *
 * @package BLZ_AFFILIATION
 */
class AdminPage {
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
        add_menu_page(CONFIG["plugin_name"], CONFIG["plugin_name"], 'manage_options', CONFIG["plugin_suffix"]);
        foreach(CONFIG["Items"] as $item){
            $className = "BLZ_AFFILIATION\\AdminUserInterface\\Settings\\". $item["class_name"];
            $class = new $className;
            add_submenu_page(CONFIG["plugin_suffix"], $item["name"],$item["name"], 'manage_options', $item["suffix"], [ $class, 'render']);
        }
            
	}

    public static function custom_enqueue()
    {
        
        if (is_admin()) {
            # enqueue scripts
            wp_enqueue_script(
                'blz-affiliation-adminjs',
                PLUGIN_URI ."src/assets/js/admin.js",
                [],
                PLUGIN_VERSION,
                true
            );
        }
        
    }
    
}