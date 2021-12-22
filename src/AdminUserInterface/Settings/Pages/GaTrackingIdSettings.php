<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Pages;

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

    private $name;
    private $slug;
    
    private $current;
    private $option_name;

    private $output =
     <<<HTML
    <form method="post" id="GaTrakingIdSettings" name="GaTrakingIdSettings" action="{{ link }}">
        <input type="hidden" name="{{ slug }}-sendForm" value="OK" />
        {{ tabs }}
        <div class="{{ slug }}-container">
            <h2>{{ title }}</h2>
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
    
	function __construct($slug, $settings) {
        
        $this->slug      = $slug;
        $this->settings  = $settings;

        $this->setCurrentObjects();
    }

	/**
     * Print page if have correct permission
    **/
    public function render()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Non hai i permessi per visualizzare questa pagina');
        } else{
            $tabs =  (new Tab($this->settings,$this->current))->render();

            echo str_replace(
                [ 
                    '{{ link }}',
                    '{{ slug }}',
                    '{{ tabs }}',
                    '{{ title }}',
                    '{{ ActivationTableImport }}',
                    '{{ TemplateTable }}',
                    '{{ ActivationTable }}',
                    '{{ wp_nonce }}'
                ],
                [ 
                    esc_html( admin_url( 'admin.php?page='.$_GET["page"].'&tab='.$this->current["tab"]["slug"].'&marketplace='.$this->current["marketplace"]["slug"]."#tabella" ) ),
                    $this->slug, 
                    $tabs,
                    $this->current["tab"]["description"] . $this->current["marketplace"]["description"],
                    (new TemplateTable($this->option_name,$this->current))->render(),
                    (new ActivationTableImport($this->option_name."_activation_import"))->render(),
                    (new ActivationTable($this->option_name,$this->current))->render(),
                    wp_nonce_field( $this->slug.'-settings-save', $this->slug.'-custom-message')
                ],
                $this->output
            );
        }

    }

    /**
     * Set current objects
    **/
    public function setCurrentObjects()
    {
        $this->current["tab"] = (isset($_GET['tab'])) ? Helper::findbySlug($this->settings["tabs"],$_GET["tab"]) : $this->settings["tabs"][0];
        $this->current["marketplace"] = (isset($_GET['marketplace'])) ? Helper::findbySlug($this->current["tab"]["marketplaces"],$_GET["marketplace"]) : $this->current["tab"]["marketplaces"][0];
        $this->option_name = $this->slug."-".$this->current["tab"]["slug"]."-".$this->current["marketplace"]["slug"];

    }
    
}