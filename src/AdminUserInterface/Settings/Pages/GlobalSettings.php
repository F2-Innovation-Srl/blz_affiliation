<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Pages;

use BLZ_AFFILIATION\Utils\Config;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\StyleSettingsTable;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\GlobalSettingsTable;
/**
 * Class GlobalSettings
 *
 * @package BLZ_AFFILIATION
 */
class GlobalSettings {

    public $settings;
    protected $option_name;
    
	function __construct($slug, $settings) {

        $this->settings  = $settings; 
        $this->option_name = $slug;
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
                    <?php ( new StyleSettingsTable( $this->option_name ))->render();  ?>
                </div>

                <div><hr></div>

                <?php 
                    wp_nonce_field( $this->option_name.'-settings-save', $this->option_name.'-custom-message' );
                    submit_button();
                ?>
            </form><!-- .wrap -->
            <?php
        }

    }

}