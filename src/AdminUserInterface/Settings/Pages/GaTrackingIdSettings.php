<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Pages;

use BLZ_AFFILIATION\Utils\Helper;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\ActivationTable;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\TemplateTable;
/**
 * Class GaTrackingIdSettings
 *
 * @package BLZ_AFFILIATION
 */
class GaTrackingIdSettings {

    protected $name;
    protected $slug;
    public $settings;
    protected $tabs;
    protected $marketplaces;
    protected $current;
    protected $option_name;
	/**
	 * AdminPage constructor.
	 */
    
	function __construct($slug, $settings) {
        
        $this->slug      = $slug;
        $this->settings  = $settings;

        $this->tabs = $settings["tabs"];

        $this->current["tab"] = (isset($_GET['tab'])) ? Helper::findbySlug($this->tabs,$_GET["tab"]) : $this->tabs[0];
        $this->current["marketplace"] = (isset($_GET['marketplace'])) ? Helper::findbySlug($this->current["tab"]["marketplaces"],$_GET["marketplace"]) : $this->current["tab"]["marketplaces"][0];
        $this->marketplaces = $this->current["tab"]["marketplaces"];
        $this->option_name = $this->slug."-".$this->current["tab"]["slug"]."-".$this->current["marketplace"]["slug"];
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
            <form method="post" id="GaTrakingIdSettings" name="GaTrakingIdSettings" action="<?php echo esc_html( admin_url( 'admin.php?page='.$_GET["page"].'&tab='.$this->current["tab"]["slug"].'&marketplace='.$this->current["marketplace"]["slug"]."#tabella" ) ); ?>">
                <input type="hidden" name="<?php echo $this->slug;?>-sendForm" value="OK" />
                <?php $this->printTabs(); ?>
                <div class="<?php echo $this->slug;?>-container">
                    <h2><?php echo $this->current["tab"]["description"] . $this->current["marketplace"]["description"];?></h2>
                    <?php 
                    (new TemplateTable($this->option_name,$this->current))->render(); 
                    if (!empty($this->current["marketplace"]["ga_event_template"]) || !empty($this->current["marketplace"]["tracking_id"]) )
                        (new ActivationTable($this->option_name,$this->current))->render(); 
                    ?>
                </div>
                <div><hr></div>
                <?php 
                    wp_nonce_field( $this->slug.'-settings-save', $this->slug.'-custom-message' );
                ?>
            </form><!-- .wrap -->
            <?php
        }

    }

    

    private function printTabs() {
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach($this->tabs as $tab) {
            $classTab = ( $tab["slug"] == $this->current["tab"]["slug"] ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$_GET["page"]."&tab=".$tab["slug"]."&marketplace=".$this->current["marketplace"]["slug"]."'>".$tab["name"]."</a>";
        }
        echo '</h2>';
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach($this->marketplaces as $tab) {
            $classTab = ( $tab["slug"] == $this->current["marketplace"]["slug"] ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$_GET["page"]."&tab=".$this->current["tab"]["slug"]."&marketplace=".$tab["slug"]."'>".$tab["name"]."</a>";
        }
        echo '</h2>';
    }

    
}