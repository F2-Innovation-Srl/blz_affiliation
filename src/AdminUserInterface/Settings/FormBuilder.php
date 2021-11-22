<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/**
 * Class FormBuilder
 *
 * @package BLZ_AFFILIATION
 */
class FormBuilder {
    public $page = "blz-affiliation";
    protected $marketplace;
	/**
	 * AdminPage constructor.
	 */
	function __construct($marketplace) {
        $this->marketplace = $marketplace;
	}

   /**
     * Print form
    **/
    public function printForm()
    {
        $this->printParam("Il marketplace ".$this->marketplace. " è attivo?","boolean","blz-affiliation-".$this->marketplace."-active"); 
        $this->printParam("Default TrackingID per ".$this->marketplace,"text","blz-affiliation-".$this->marketplace."-default"); 
        $this->printParam("Default Ga Event per ".$this->marketplace,"text","blz-affiliation-".$this->marketplace."-default"); 
        $this->printTypeTracking();
        $this->printUsersList();
    }


    private function printTypeTracking(){
        $blz_affiliation_type=get_option("blz-affiliation-type",'');
        ?>
        <div class="options"><p><label><strong>Seleziona tipologia di tracciamento:</strong></label><br>
            <select name="blz-affiliation-type">
            <option value="">Seleziona un tipologia</option>
             <option value="<?php echo $blz_affiliation_type ?>" <?php echo ($blz_affiliation_type == "POSTTYPE") ? "selected" : ""?>>POSTTYPE</option>
             <option value="<?php echo $blz_affiliation_type ?>" <?php echo ($blz_affiliation_type == "CATEGORY") ? "selected" : ""?>>CATEGORY</option>
            </select>
        </p></div>
        <?php
    }

    private function printUsersList(){
        ?>
        <div class="options"><p><label><strong>Elenco Utenti:</strong></label><br>
            <select name="blz-affiliation-users">
                <option value="0">Seleziona un utente</option>
            <?php
            $blogusers = get_users(['role__in' => ['author', 'subscriber']]);
            // Array of WP_User objects.
            foreach ( $blogusers as $user ) 
                echo '<option name="' . esc_html( $user->display_name ) . '">' . esc_html( $user->display_name ) . '</option>';
            ?>
            </select>
        </p></div>
        <?php
    }

    private function printParam($label,$type,$name){
        ?>
        <div class="options"><p><label><strong><?php echo $label?>:</strong></label><br>
        <?php 
        switch ($type) {
            case "number":
                ?>
                <input type="number" name="<?php echo $name?>" value="<?php echo get_option($name,'')?>" />
                <?php
                break;
            case "text":
                ?>
                <input type="text" name="<?php echo $name?>" value="<?php echo get_option($name,'')?>" />
                <?php
                break;
            case "boolean":
                ?>
                SI <input type="radio" name="<?php echo $name?>" <?php echo (get_option($name,'true') == "true" ) ? "checked" : ""?> value="true" />
                NO <input type="radio" name="<?php echo $name?>" <?php echo (get_option($name,'true') == "false") ? "checked" : ""?> value="false" />
                <?php
                break;
        }
        ?>
        </p></div>
        <?php
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