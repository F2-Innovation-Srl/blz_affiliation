<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Pages;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Capability;
use BLZ_AFFILIATION\Utils\Helper;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\ActivationTable;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\ActivationTableImport;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\TemplateTable;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Pages\Partials\Tab;
/**
 * Class GaTrackingIdSettings
 *
 * @package BLZ_AFFILIATION
 */
class GaTrackingIdSettings {

    public $settings;
    protected $title;
    private $is_valid_config;
    private $name;
    private $slug;
    
    private $current;
    private $option_name;

    private $output =
     <<<HTML
     <div><h1>{{ title }}</h1></div>
    <form method="post" id="GaTrackingIdSettings" name="GaTrackingIdSettings" action="{{ link }}">
        <input type="hidden" name="{{ slug }}-sendForm" value="OK" />
        {{ tabs }}
        <h3>{{ description }}</h3>
        <div class="{{ slug }}-container">
            {{ TemplateTable }}
            {{ ActivationTableImport }}
            {{ ActivationTable }}
            
        </div>
        <div><hr></div>
        {{ wp_nonce }}
    </form>
    HTML;

	/**
	 * AdminPage constructor.
	 */
    
	function __construct($is_valid_config,$title, $slug, $settings) {
        $this->is_valid_config     = $is_valid_config;
        $this->title               = $title;
        $this->slug                = $slug;
        $this->settings            = $settings;


        $this->setCurrentObjects();
    }

	/**
     * Print page if have correct permission
    **/
    public function render() {
        
        if( !$this->is_valid_config ) { 

            wp_die('Per utilizzare il plugin occorre prima caricare le configurazioni');
        }

        if( !current_user_can( Capability::AFFILIATION_CAP ) ) {

            wp_die('Non hai i permessi per visualizzare questa pagina');
        } 

        
        $tabs =  (new Tab($this->settings,$this->current))->render();

        echo str_replace(
            [ 
                '{{ title }}',
                '{{ link }}',
                '{{ slug }}',
                '{{ tabs }}',
                '{{ description }}',
                '{{ TemplateTable }}',
                '{{ ActivationTableImport }}',
                '{{ ActivationTable }}',
                '{{ wp_nonce }}'
            ],
            [ 
                $this->title,
                esc_html( admin_url( 'admin.php?page='.$_GET["page"].'&tab='.$this->current["tab"]["slug"].'&marketplace='.$this->current["marketplace"]["slug"]."#tabella" ) ),
                $this->slug, 
                $tabs,
                $this->current["tab"]["description"] . " " . $this->current["marketplace"]["description"],
                (new TemplateTable($this->option_name,$this->current))->render(),
                (new ActivationTableImport($this->option_name))->render(),
                (new ActivationTable($this->option_name,$this->current))->render(),
                wp_nonce_field( $this->slug.'-settings-save', $this->slug.'-custom-message')
            ],
            $this->output
        );
    }

    /**
     * Set current objects
    **/
    public function setCurrentObjects()
    {
        $this->current["tab"]         = (isset($_GET['tab'])) ? Helper::findbySlug($this->settings["tabs"],$_GET["tab"]) : $this->settings["tabs"][0];
        $this->current["marketplace"] = (isset($_GET['marketplace'])) ? Helper::findbySlug($this->current["tab"]["marketplaces"],$_GET["marketplace"]) : $this->current["tab"]["marketplaces"][0];
        $this->option_name            = $this->slug."-".$this->current["tab"]["slug"]."-".$this->current["marketplace"]["slug"];
    }
    
}