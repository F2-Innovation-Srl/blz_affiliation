<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

use BLZ_AFFILIATION\Utils\Config;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\GeneralSettingsTable;
/**
 * Class GeneralSettings
 *
 * @package BLZ_AFFILIATION
 */
class GeneralSettings {

    protected $item;
    protected $option_name;
	/**
	 * AdminPage constructor.
	 */
	function __construct() {
        $this->item = Config::findbySuffix(CONFIG["Items"],$_GET["page"]);
        $this->option_name = $this->item["suffix"];
    }

	/**
     * Print page if have correct permission
    **/
    public function render()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Non hai i permessi per visualizzare questa pagina');
        } else{
            ?>
            <form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page='.$_GET["page"]))?>">
                <input type="hidden" name="<?php echo $this->item["suffix"];?>-sendForm" value="OK" />
                <div class="<?php echo $this->item["suffix"];?>-container">
                    <h2>General Settings</h2>
                    <?php 
                    (new GeneralSettingsTable($this->option_name))->render(); 
                    ?>
                </div>
                <div><hr></div>
                <?php 
                    wp_nonce_field( $this->item["suffix"].'-settings-save', $this->item["suffix"].'-custom-message' );
                    submit_button();
                ?>
            </form><!-- .wrap -->
            <?php
        }

    }

    
}