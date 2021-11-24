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
    protected $current_tab;
    protected $current_parent_tab;
	/**
	 * AdminPage constructor.
	 */
	function __construct() {
        $this->tabs = findInConfig(CONFIG["Items"],"tabs",$_GET["page"]);
        $this->marketplaces = findInConfig(CONFIG["Items"],"marketplaces",$_GET["page"]);
       
        $this->current_tab = (isset($_GET['tab'])) ? $this->tabs[array_search($_GET["tab"], array_column($this->tabs, 'suffix'))] : $this->marketplaces[0];
        $this->current_parent_tab = (isset($_GET['parent_tab'])) ? $this->marketplaces[array_search($_GET["parent_tab"], array_column($this->marketplaces, 'suffix'))] : $this->tabs[0];
    }

	private function findInConfig($obj,$key,$val){
        return $obj[array_search($val, array_column($obj, 'suffix'))][$key];
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
        
        $formBuilder = new FormBuilder($this->current_tab["slug"]);
        if (isset($_POST["blz-affiliation-sendForm"])) $formBuilder->saveForm();
        
        ?>
        <form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page='.$_GET["page"].'&tab='.$this->current_tab["slug"].'&parent_tab='.$this->current_parent_tab ) ); ?>">
            <input type="hidden" name="blz-affiliation-sendForm" value="OK" />
            <?php $this->printTabs(); ?>
            <div class="blz-affiliation-container">
                <h2><?php echo $this->current_tab["description"];?></h2>
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
        foreach($this->tabs as $tabs) {
            $classTab = ( $tabs["slug"] == $this->current_parent_tab ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$_GET["page"]."&tab=".$this->current_tab."&parent_tab=".$tabs["slug"]."'>".$tabs["name"]."</a>";
        }
        echo '</h2>';
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach($this->marketplaces as $tabs) {
            $classTab = ( $tabs["slug"] == $this->current_tab["slug"] ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$_GET["page"]."&tab=".$tabs["slug"]."&parent_tab=".$this->current_parent_tab."'>".$tabs["name"]."</a>";
        }
        echo '</h2>';
    }
    
    
}