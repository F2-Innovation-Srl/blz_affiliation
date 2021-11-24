<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/**
 * Class AdminPage
 *
 * @package BLZ_AFFILIATION
 */
class AdminPage {
    public $page = "blz-affiliation";
    protected $marketplaces;
    protected $tabs;
    protected $current_tab;
    protected $current_sub_tab;
	/**
	 * AdminPage constructor.
	 */
	function __construct($config) {

        $this->tabs = $config->tabs;
        $this->marketplaces = $config->marketplaces;
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
            $this->printPage();
        }

    }

    /**
     * Print Page
    **/
    private function printPage()
    {
        
        $this->current_tab = (isset($_GET['tab'])) ? $_GET['tab'] : array_key_first($this->marketplaces);
        
        $formBuilder = new FormBuilder($this->current_tab);
        if (isset($_POST["blz-affiliation-sendForm"])) $formBuilder->saveForm();
        
        ?>
        <form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page='.$this->page.'&tab='.$this->current_tab ) ); ?>">
            <input type="hidden" name="blz-affiliation-sendForm" value="OK" />
            <?php $this->printTabs(); ?>
            <div class="blz-affiliation-container">
                <h2><?php echo $this->marketplaces[$this->current_tab]->getPanelDescription();?></h2>
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
        foreach($this->marketplaces as $marketplace) {
            $classTab = ( $marketplace->getPanelName() == $this->current_tab ) ? " nav-tab-active" : "";
            echo "<a class='nav-tab".$classTab."' href='?page=".$this->page."&tab=".$marketplace->getPanelName()."'>".$marketplace->getPanelName()."</a>";
        }
        echo '</h2>';
    }
    
    
}