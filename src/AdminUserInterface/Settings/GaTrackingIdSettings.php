<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

use BLZ_AFFILIATION\Utils\Config;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\ActivationTable;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\TemplateTable;
/**
 * Class GaTrackingIdSettings
 *
 * @package BLZ_AFFILIATION
 */
class GaTrackingIdSettings {

    protected $item;
    protected $tabs;
    protected $marketplaces;
    protected $current;
    protected $option_name;
	/**
	 * AdminPage constructor.
	 */
	function __construct() {
        $this->item = Config::findbySuffix(CONFIG["Items"],$_GET["page"]);
        $this->tabs = $this->item["settings"]["tabs"];
        $this->current["tab"] = (isset($_GET['tab'])) ? Config::findbySuffix($this->tabs,$_GET["tab"]) : $this->tabs[0];
        $this->current["marketplace"] = (isset($_GET['marketplace'])) ? Config::findbySuffix($this->current["tab"]["marketplaces"],$_GET["marketplace"]) : $this->current["tab"]["marketplaces"][0];
        $this->marketplaces = $this->current["tab"]["marketplaces"];
        $this->option_name = $this->item["suffix"]."-".$this->current["tab"]["suffix"]."-".$this->current["marketplace"]["suffix"];
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
            <form method="post" id="GaTrakingIdSettings" name="GaTrakingIdSettings" action="<?php echo esc_html( admin_url( 'admin.php?page='.$_GET["page"].'&tab='.$this->current["tab"]["suffix"].'&marketplace='.$this->current["marketplace"]["suffix"]."#tabella" ) ); ?>">
                <input type="hidden" name="<?php echo $this->item["suffix"];?>-sendForm" value="OK" />
                <?php $this->printTabs(); ?>
                <div class="<?php echo $this->item["suffix"];?>-container">
                    <h2><?php echo $this->current["tab"]["description"] . " per i " .$this->current["marketplace"]["description"];?></h2>
                    <?php 
                    (new TemplateTable($this->option_name,$this->current))->render(); 
                    if (!empty($this->current["marketplace"]["ga_event_template"]) || !empty($this->current["marketplace"]["tracking_id"]) )
                        (new ActivationTable($this->option_name,$this->current))->render(); 
                    ?>
                </div>
                <div><hr></div>
                <?php 
                    wp_nonce_field( $this->item["suffix"].'-settings-save', $this->item["suffix"].'-custom-message' );
                ?>
            </form><!-- .wrap -->
            <?php
        }

    }

    

    private function printTabs() {
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach($this->tabs as $tab) {
            $classTab = ( $tab["suffix"] == $this->current["tab"]["suffix"] ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$_GET["page"]."&tab=".$tab["suffix"]."&marketplace=".$this->current["marketplace"]["suffix"]."'>".$tab["name"]."</a>";
        }
        echo '</h2>';
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach($this->marketplaces as $tab) {
            $classTab = ( $tab["suffix"] == $this->current["marketplace"]["suffix"] ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$_GET["page"]."&tab=".$this->current["tab"]["suffix"]."&marketplace=".$tab["suffix"]."'>".$tab["name"]."</a>";
        }
        echo '</h2>';
    }

    
}