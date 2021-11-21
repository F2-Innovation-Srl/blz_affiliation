<?php
namespace BLZ_AFFILIATION\AdminUserInterface;

/**
 * Class AdminPage
 *
 * @package EMAILDATABASECONNECT
 */
class PluginSettings {
    public $page = "blz-affiliation";
    protected $marketplaces;
    protected $current_tab;
    protected $types;
	/**
	 * AdminPage constructor.
	 */
	function __construct($marketplaces) {
        $this->marketplaces = $marketplaces;
        $this->types = ["GA TRACKING", "TRACKING ID"];
        # set admin actions callback
        add_action('admin_menu', [ $this, 'adminMenu' ]);
	}

	/**
     * Invoked on admin_menu action
     * Create admin menu 
    **/
	public function adminMenu() {
        add_menu_page('Blazemedia Affilitate', 'Blz Affilitate', 'manage_options', $this->page, [ $this, 'render']);

	}

	/**
     * Print page if have correct permission
    **/
    public function render()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Non hai i permessi per visualizzare questa pagina');
        } else{
            $this->current_tab = (isset($_GET['tab'])) ? $_GET['tab'] : array_key_first($this->marketplaces);
            if (isset($_POST["blz-affiliation-sendForm"])) $this->saveForm();
            $this->printForm();
        }

    }

   /**
     * Print form
    **/
    private function printForm()
    {
        /*
        return array_reduce( $this->marketplaces, function( $result, $marketplace ) {

            $base = 'BLZ_AFFILIATION\\AffiliateMarketing\\Marketplaces\\';
            
            $result = array_merge( $result, ( new ($base.$marketplace)( $this->request ) )->getOffers() );

            return $result;

        }, []);
        */
        ?>


        <div class="wrap"><h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page='.$this->page.'&tab='.$this->current_tab ) ); ?>">
            <input type="hidden" name="edc-sendForm" value="OK" />
            <?php 
            $this->printTabs();
            $description = $this->marketplaces[$this->current_tab]->getPanelDescription();
            $active = $this->marketplaces[$this->current_tab]->getActive();
            $params = [];
            ?>
            
            <div class="edc-container">
                <h2><?php echo $description;?></h2>
                <?php 
                    $this->printParams("Attivo","boolean","edc-".$this->current_tab."_active",$active); 
                    foreach($params as $key => $value) 
                        $this->printParams($value["label"],$value["type"],"edc-param-".$key,$value["value"]); 
                ?>
            </div>
            <div><hr></div>
            <?php 
                wp_nonce_field( 'blz-affiliation-settings-save', 'blz-affiliation-custom-message' );
                submit_button();
            ?>
        </form></div><!-- .wrap -->
    <?php
    }


    private function printParams($label,$type,$name,$value){
        ?>
        <div class="options"><p><label><strong><?php echo $label?>:</strong></label><br>
        <?php 
        switch ($type) {
            case "number":
                ?>
                <input type="number" style="width:70px" name="<?php echo $name?>" value="<?php echo $value?>" />
                <?php
                break;
            case "string":
                ?>
                <input type="text" name="<?php echo $name?>" value="<?php echo $value?>" />
                <?php
                break;
            case "boolean":
                ?>
                SI <input type="radio" name="<?php echo $name?>" <?php echo ($value == "true") ? "checked" : ""?> value="true" />
                NO <input type="radio" name="<?php echo $name?>" <?php echo ($value == "false") ? "checked" : ""?> value="false" />
                <?php
                break;
        }
        ?>
        </p></div>
        <?php
    }
    
    private function printTabs($current = 'homepage' ) {
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach($this->marketplaces as $marketplace) {
            $classTab = ( $marketplace->getPanelName() == $this->current_tab ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$this->page."&tab=".$marketplace->getPanelName()."'>".$marketplace->getPanelName()."</a>";
    
        }
        echo '</h2>';
    }
    /**
     * Save form
    **/
    private function saveForm()
    {
    
        if (isset($_POST["blz-affiliation-".$this->current_tab."_active"])){
            $this->marketplaces[$this->current_tab]->setActive($_POST["blz-affiliation-".$this->current_tab."_active"]);
            update_option($this->current_tab."_active",$_POST["blz-affiliation-".$this->current_tab."_active"]);
        }

        foreach (array_filter($_POST, function($k) { return strpos($k, "blz-affiliation-param-") !== false; }, ARRAY_FILTER_USE_KEY) as $key => $val){
            $this->marketplaces[$this->current_tab]->setParamValue(str_replace("blz-affiliation-param-","",$key),$val);
            update_option(str_replace("blz-affiliation-param-","",$key),$val);
            //$this->current_tab
        }
        echo "<div class=\"updated notice\"><p>Dati salvati con successo</p></div>";
    }

    
    
}