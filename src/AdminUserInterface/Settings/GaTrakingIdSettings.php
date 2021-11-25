<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

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
        $this->item = \BLZ_AFFILIATION\Utils\Settings::findbySuffix(CONFIG["Items"],$_GET["page"]);
        $this->tabs = $this->item["settings"]["tabs"];
        $this->marketplaces = $this->item["settings"]["marketplaces"];
        $this->current = [
            "tab" => (isset($_GET['tab'])) ? \BLZ_AFFILIATION\Utils\Settings::findbySuffix($this->marketplaces,$_GET["tab"]) : $this->marketplaces[0],
            "sub_tab" => (isset($_GET['sub_tab'])) ? \BLZ_AFFILIATION\Utils\Settings::findbySuffix($this->tabs,$_GET["sub_tab"]) : $this->tabs[0]
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
            $this->printPage();
        }

    }

    /**
     * Print Page
    **/
    private function printPage()
    {
        if (isset($_POST[$this->item["prefix"]."-sendForm"])) $this->saveForm();
        ?>
        <form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page='.$_GET["page"].'&tab='.$this->current["tab"]["suffix"].'&sub_tab='.$this->current["sub_tab"]["suffix"] ) ); ?>">
            <input type="hidden" name="<?php echo $this->item["prefix"];?>-sendForm" value="OK" />
            <?php $this->printTabs(); ?>
            <div class="<?php echo $this->item["prefix"];?>-container">
                <h2><?php echo $this->current["sub_tab"]["description"] . " per i " .$this->current["tab"]["description"];?></h2>
                <?php $this->printTable(); ?>
                <?php $this->printTemplate(); ?>
            </div>
            <div><hr></div>
            <?php 
                wp_nonce_field( $this->item["prefix"].'-settings-save', $this->item["prefix"].'-custom-message' );
                submit_button();
            ?>
        </form></div><!-- .wrap -->
    <?php
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

    private function printTable() {
       $rules = $this->getRules();
       ?>
       <div><h2>Tabella di attivazione</h2></div>
        <table>
            <tr valign="top" style="text-align:left">
                <th>Attivatore</th><th>Regola</th><th>Valore GA</th><th>Valore TRK_ID</th><th>Label GA</th><th>Label TRK_ID</th>                        
            </tr>
            <?php foreach( $rules as $idx => $rule ) : ?>

                <tr valign="top">                    
                    <td><?php echo printActivators("rules_attivatore".$idx,$rule['attivatore']) ?></td>
                    <td><input type="text" name="rules_regola<?=$idx?>" value="<?=$rule['regola']?>" /></td>
                    <td><input type="text" name="rules_ga_val<?=$idx?>" value="<?=$rule['ga_val']?>" /></td>
                    <td><input type="text" name="rules_trk_val<?=$idx?>" value="<?=$rule['trk_val']?>" /></td>
                    <td><input type="text" name="rules_ga_label<?=$idx?>" value="<?=$rule['ga_label']?>" /></td>
                    <td><input type="text" name="rules_trk_label<?=$idx?>" value="<?=$rule['trk_label']?>" /></td>
                    <td><?php submit_button('Aggiorna', 'primary', 'submit', false ); ?></td> 
                </tr>

            <?php endforeach; ?>
            <tr valign="top">                    
                <td><?php echo printActivators("rules_attivatore_new") ?></td>
                    <td><input type="text" name="rules_regola_new" value="" /></td>
                    <td><input type="text" name="rules_ga_val_new" value="" /></td>
                    <td><input type="text" name="rules_trk_val_new" value="" /></td>
                    <td><input type="text" name="rules_ga_label_new" value="" /></td>
                    <td><input type="text" name="rules_trk_label_new" value="" /></td>
                <td><?php submit_button('Aggiungi', 'primary', 'submit', false ); ?></td>                    
            </tr>
        </table>
       <?php
    }

    private function getRules(){

        $rules = get_option($this->option_name);

        $rules = ($rules) ? array_map( function ( $rule, $idx  )  {

            return [
                'attivatore' => isset( $_POST[ 'rules_attivatore'.$idx ] ) ? $_POST[ 'rules_attivatore'.$idx ] : $rule['attivatore'],
                'regola' => isset( $_POST[ 'rules_regola'.$idx ] ) ? $_POST[ 'rules_regola'.$idx ] : $rule['regola'],
                'ga_val' => isset( $_POST[ 'rules_ga_val'.$idx ] ) ? $_POST[ 'rules_ga_val'.$idx ] : $rule['ga_val'],
                'trk_val' => isset( $_POST[ 'rules_trk_val'.$idx ] ) ? $_POST[ 'rules_trk_val'.$idx ] : $rule['trk_val'],
                'ga_label' => isset( $_POST[ 'rules_ga_label'.$idx ] ) ? $_POST[ 'rules_ga_label'.$idx ] : $rule['ga_label'],
                'trk_label' => isset( $_POST[ 'rules_trk_label'.$idx ] ) ? $_POST[ 'rules_trk_label'.$idx ] : $rule['trk_label'],
            ];

        }, $rules, array_keys($rules) ) : [];
        if( !empty( $_POST['rules_attivatore_new'] ) && !empty( $_POST['rules_regola_new'] ) ) {

            $rules[] = [
                'attivatore' => $_POST['rules_attivatore_new'],
                'regola' => $_POST['rules_regola_new'],
                'ga_val' => $_POST['rules_ga_val_new'],
                'trk_val' => $_POST['rules_trk_val_new'],
                'ga_label' => $_POST['rules_ga_label_new'],
                'trk_label' => $_POST['rules_trk_label_new']
            ];
        }

        update_option($this->option_name, $rules );

        return $rules;

    }

    private function printActivators($name,$value = null){
        ?>
        <select name="<?php echo $name?>"><option value="">Seleziona un attivatore</option>
            <?php 
            $listActivator = ["POSTTYPE","CATEOGORY","TAXONOMY","TAG","USERS"];
            foreach( $listActivator as $activator) :?>
                <option value="<?php echo $activator?>" <?php ($value == $activator) ? "selected" : ""?> ><?php echo $activator?></option>
            <?php endforeach;?>
        </select>
        <?php
    }

    private function printUsers($value){
        ?>
            <select name="<?php echo $name?>"><option value="0">Seleziona un utente</option>
            <?php
            $blogusers = get_users(['role__in' => ['author', 'subscriber']]);
            foreach( $blogusers as $user) :?>
                <option value="<?php echo $user->display_name?>" <?php ($value == $user->display_name) ? "selected" : ""?> ><?php echo $user->display_name?></option>
            <?php endforeach;?>
            </select>
        <?php
    }

    private function printTemplate() {
        ?>
        <div><h2>Template</h2></div>
        <table>
            <tr valign="top" style="text-align:left">
               <td>GA EVENT</td>
               <td><input type="text" readonly value="<?php echo $this->current["tab"]["ga_event_template"];?>"></td>
            </tr>
            <tr valign="top" style="text-align:left">
               <td>TRACKING ID</td>
               <td><input type="text" readonly value="<?php echo $this->current["tab"]["tracking_id"];?>"></td>
            </tr>
        </table>
        <?php
    }
    
}