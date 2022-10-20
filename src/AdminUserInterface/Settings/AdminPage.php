<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

class AdminPage {

    function __construct() {
        
        /// aggiunge la voce del plugin nel menu 
        add_action( 'admin_menu', [ $this, 'adminMenu' ]);
    }

    /**
     * Invoked on admin_menu action
     * Create admin menu 
     */
    public function adminMenu() {

        $config = Config::loadSettings();

        add_menu_page( $config->plugin_name, $config->plugin_name, 'edit_blz_affiliation', $config->plugin_slug );

        /// aggiunge le pagine al menu del plugin, 
        /// come da configurazione
        foreach( $config->pages as $page ) {

            add_submenu_page( 
                $config->plugin_slug,
                $page->name, 
                $page->name,
                'edit_blz_affiliation', 
                $page->slug,
                [ $page->controller, 'render' ]
            ); 
        }
    }

}