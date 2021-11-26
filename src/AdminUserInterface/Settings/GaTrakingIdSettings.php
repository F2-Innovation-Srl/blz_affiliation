<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

use BLZ_AFFILIATION\Utils\Config;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\ActivationTable;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\TemplateTable;
/**
 * Class GaTrakingIdSettings
 *
 * @package BLZ_AFFILIATION
 */
class GaTrakingIdSettings {

    protected $item;
    protected $marketplaces;
    protected $tabs;
    protected $current;
    protected $option_name;
	/**
	 * AdminPage constructor.
	 */
	function __construct() {
        $this->item = Config::findbySuffix(CONFIG["Items"],$_GET["page"]);
        $this->tabs = $this->item["settings"]["tabs"];
        $this->marketplaces = $this->item["settings"]["marketplaces"];
        $this->current = [
            "tab" => (isset($_GET['tab'])) ? Config::findbySuffix($this->marketplaces,$_GET["tab"]) : $this->marketplaces[0],
            "sub_tab" => (isset($_GET['sub_tab'])) ? Config::findbySuffix($this->tabs,$_GET["sub_tab"]) : $this->tabs[0]
        ];
        $this->option_name = $this->item["suffix"]."-".$this->current["tab"]["suffix"]."-".$this->current["sub_tab"]["suffix"];
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
            <form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page='.$_GET["page"].'&tab='.$this->current["tab"]["suffix"].'&sub_tab='.$this->current["sub_tab"]["suffix"] ) ); ?>">
                <input type="hidden" name="<?php echo $this->item["suffix"];?>-sendForm" value="OK" />
                <?php $this->printTabs(); ?>
                <div class="<?php echo $this->item["suffix"];?>-container">
                    <h2><?php echo $this->current["sub_tab"]["description"] . " per i " .$this->current["tab"]["description"];?></h2>
                    <?php 
                    (new ActivationTable($this->item["suffix"]."_",$this->getAndSetAttivationsRows()))->render(); 
                    (new TemplateTable())->render(); 
                    ?>
                </div>
                <div><hr></div>
                <?php 
                    wp_nonce_field( $this->item["suffix"].'-settings-save', $this->item["suffix"].'-custom-message' );
                    submit_button();
                ?>
            </form></div><!-- .wrap -->
            <?php
        }

    }

    

    private function printTabs() {
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach($this->marketplaces as $tab) {
            $classTab = ( $tab["suffix"] == $this->current["tab"]["suffix"] ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$_GET["page"]."&tab=".$tab["suffix"]."&sub_tab=".$this->current["sub_tab"]["suffix"]."'>".$tab["name"]."</a>";
        }
        echo '</h2>';
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach($this->tabs as $tab) {
            $classTab = ( $tab["suffix"] == $this->current["sub_tab"]["suffix"] ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$_GET["page"]."&tab=".$this->current["tab"]["suffix"]."&sub_tab=".$tab["suffix"]."'>".$tab["name"]."</a>";
        }
        echo '</h2>';
    }

 

    private function getAndSetAttivationsRows(){

        $activationRows = get_option($this->option_name);

        $activationRows = ($activationRows) ? array_map( function ( $activationRows, $idx  )  {

            return [
                'attivatore' => isset( $_POST[$this->item["suffix"]. '_attivatore'.$idx ] ) ? $_POST[$this->item["suffix"]. '_attivatore'.$idx ] : $activationRow['attivatore'],
                'regola' => isset( $_POST[ $this->item["suffix"].'_regola'.$idx ] ) ? $_POST[ $this->item["suffix"].'_regola'.$idx ] : $activationRow['regola'],
                'ga_val' => isset( $_POST[ $this->item["suffix"].'_ga_val'.$idx ] ) ? $_POST[$this->item["suffix"]. '_ga_val'.$idx ] : $activationRow['ga_val'],
                'trk_val' => isset( $_POST[ $this->item["suffix"].'_trk_val'.$idx ] ) ? $_POST[ $this->item["suffix"].'_trk_val'.$idx ] : $activationRow['trk_val'],
                'ga_label' => isset( $_POST[ $this->item["suffix"].'_ga_label'.$idx ] ) ? $_POST[ $this->item["suffix"].'_ga_label'.$idx ] : $activationRow['ga_label'],
                'trk_label' => isset( $_POST[ $this->item["suffix"].'_trk_label'.$idx ] ) ? $_POST[$this->item["suffix"]. '_trk_label'.$idx ] : $activationRow['trk_label'],
            ];

        }, $activationRows, array_keys($activationRows) ) : [];
        if( !empty( $_POST['_attivatore_new'] ) && !empty( $_POST['_regola_new'] ) ) {

            $activationRows[] = [
                'attivatore' => $_POST[$this->item["suffix"].'_attivatore_new'],
                'regola' => $_POST[$this->item["suffix"].'_regola_new'],
                'ga_val' => $_POST[$this->item["suffix"].'_ga_val_new'],
                'trk_val' => $_POST[$this->item["suffix"].'_trk_val_new'],
                'ga_label' => $_POST[$this->item["suffix"].'_ga_label_new'],
                'trk_label' => $_POST[$this->item["suffix"].'_trk_label_new']
            ];
        }

        update_option($this->option_name, $activationRows );

        return $activationRows;

    }

    
}