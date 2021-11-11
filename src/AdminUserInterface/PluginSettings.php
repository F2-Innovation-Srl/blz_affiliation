<?php
namespace BLZ_AFFILIATION\AdminUserInterface;

/**
 * Class AdminPage
 *
 * @package EMAILDATABASECONNECT
 */
class PluginSettings {
    public $page = "blz-affiliation";

	/**
	 * AdminPage constructor.
	 */
	function __construct() {
        # set admin actions callback
        add_action('admin_menu', [ $this, 'adminMenu' ]);
	}

	/**
     * Invoked on admin_menu action
     * Create admin menu 
    **/
	public function adminMenu() {
        add_menu_page('Blazemedia Affilitate', 'Blz Affilitate', 'manage_options', $this->page, [ $this, 'render']);

	}

	/**
     * Print page if have correct permission
    **/
    public function render()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Non hai i permessi per visualizzare questa pagina');
        } else{
            $this->printForm();
        }

    }

    /**
     * Print form
    **/
    private function printForm()
    {
        ?>
        <div class="wrap"><h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        
    <?php
    }
    
    
}