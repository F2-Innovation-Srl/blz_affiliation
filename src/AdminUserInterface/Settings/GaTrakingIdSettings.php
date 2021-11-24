<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

use BLZ_AFFILIATION\Utils;
/**
 * Class GaTrakingIdSettings
 *
 * @package BLZ_AFFILIATION
 */
class GaTrakingIdSettings {

    protected $marketplaces;
    protected $tabs;
    protected $current;
	/**
	 * AdminPage constructor.
	 */
	function __construct() {
        $settings = Settings::findbySuffix(CONFIG["Items"],$_GET["page"]);
        $this->tabs = $settings["settings"]["tabs"];
        $this->marketplaces = $settings["settings"]["marketplaces"];
        $this->current = [
            "tab" => (isset($_GET['tab'])) ? Settings::findbySuffix($this->tabs,$_GET["tab"]) : $this->tabs[0],
            "sub_tab" => (isset($_GET['sub_tab'])) ? Settings::findbySuffix($this->marketplaces,$_GET["sub_tab"]) : $this->marketplaces[0]
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Non hai i permessi per visualizzare questa pagina');
        } else{
            $this->printPage();
        }

    }

    /**
     * Print Page
    **/
    private function printPage()
    {
        
        $formBuilder = new FormBuilder($this->current["sub_tab"]["suffix"]);
        if (isset($_POST["blz-affiliation-sendForm"])) $formBuilder->saveForm();
        
        ?>
        <form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page='.$_GET["page"].'&tab='.$this->current["tab"]["suffix"].'&sub_tab='.$this->current["sub_tab"]["suffix"] ) ); ?>">
            <input type="hidden" name="blz-affiliation-sendForm" value="OK" />
            <?php $this->printTabs(); ?>
            <div class="blz-affiliation-container">
                <h2><?php echo $this->current["sub_tab"]["description"] . " per i " .$this->current["tab"]["description"];?></h2>
                <?php $formBuilder->printForm(); ?>
            </div>
            <div><hr></div>
            <?php 
                wp_nonce_field( 'blz-affiliation-settings-save', 'blz-affiliation-custom-message' );
                submit_button();
            ?>
        </form></div><!-- .wrap -->
    <?php
    }

    private function printTabs() {
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach($this->tabs as $tab) {
            $classTab = ( $tab["suffix"] == $this->current["tab"]["suffix"] ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$_GET["page"]."&tab=".$tab["suffix"]."&sub_tab=".$this->current["sub_tab"]["suffix"]."'>".$tab["name"]."</a>";
        }
        echo '</h2>';
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach($this->marketplaces as $tab) {
            $classTab = ( $tab["suffix"] == $this->current["sub_tab"]["suffix"] ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$_GET["page"]."&tab=".$this->current["tab"]["suffix"]."&sub_tab=".$tab["suffix"]."'>".$tab["name"]."</a>";
        }
        echo '</h2>';
    }
    
    
}