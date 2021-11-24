<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

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
        $settings = \BLZ_AFFILIATION\Utils\Settings::findbySuffix(CONFIG["Items"],$_GET["page"]);
        $this->tabs = $settings["settings"]["tabs"];
        $this->marketplaces = $settings["settings"]["marketplaces"];
        $this->current = [
            "tab" => (isset($_GET['tab'])) ? \BLZ_AFFILIATION\Utils\Settings::findbySuffix($this->marketplaces,$_GET["tab"]) : $this->marketplaces[0],
            "sub_tab" => (isset($_GET['sub_tab'])) ? \BLZ_AFFILIATION\Utils\Settings::findbySuffix($this->tabs,$_GET["sub_tab"]) : $this->tabs[0]
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
        if (isset($_POST["blz-affiliation-sendForm"])) $this->saveForm();
        ?>
        <form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page='.$_GET["page"].'&tab='.$this->current["tab"]["suffix"].'&sub_tab='.$this->current["sub_tab"]["suffix"] ) ); ?>">
            <input type="hidden" name="blz-affiliation-sendForm" value="OK" />
            <?php $this->printTabs(); ?>
            <div class="blz-affiliation-container">
                <h2><?php echo $this->current["sub_tab"]["description"] . " per i " .$this->current["tab"]["description"];?></h2>
                <?php $this->printTable(); ?>
                <?php $this->printTemplate(); ?>
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

    private function printTable() {
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        
        echo '</h2>';
    }

    private function printTemplate() {
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        
        echo '</h2>';
    }
    
    /**
     * Save form
    **/
    public function saveForm()
    {
        foreach (array_filter($_POST, function($k) { return strpos($k, "blz-affiliation-") !== false; }, ARRAY_FILTER_USE_KEY) as $key => $val)
            update_option($key,$val);
    
        echo "<div class=\"updated notice\"><p>Dati salvati con successo</p></div>";
    }
}