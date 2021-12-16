<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

use BLZ_AFFILIATION\Utils\Config;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\StyleSettingsTable;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\GlobalSettingsTable;
/**
 * Class GlobalSettings
 *
 * @package BLZ_AFFILIATION
 */
class GlobalSettings {

    protected $item;
    protected $option_name;
    
	function __construct() {

        $this->item        = Config::findbySuffix( CONFIG["Items"], $_GET["page"] ); 
        $this->option_name = $this->item["suffix"];
    }

    
    /**
     * Print the page if the rights are grant
     */
    public function render()
    {
        if (!current_user_can('manage_options')) {
            
            wp_die('Non hai i permessi per visualizzare questa pagina');

        } else{
            ?>
            <form method="post" action="<?= esc_html( admin_url( 'admin.php?page='.$_GET["page"])) ?>">

                <input type="hidden" name="<?= $this->option_name ?>-sendForm" value="OK" />

                <div class="<?= $this->option_name ?>-container">
                    <h2>Global Settings</h2>
                    
                    <?php ( new GlobalSettingsTable( $this->option_name ))->render();  ?>                    
                    
                    <hr>
                    
                    <h3>Link style</h3>
                    <?php ( new StyleSettingsTable( $this->option_name )."_css")->render();  ?>
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