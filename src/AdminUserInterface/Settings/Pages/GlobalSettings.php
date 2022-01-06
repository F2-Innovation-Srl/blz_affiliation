<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Pages;

use BLZ_AFFILIATION\Utils\Config;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\StyleSettingsTable;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\GlobalSettingsTable;
/**
 * Class GlobalSettings
 *
 * @package BLZ_AFFILIATION
 */
class GlobalSettings {
    protected $title;
    public $settings;
    protected $option_name;
    
    private $output =
    <<<HTML
    <div><h1>{{ title }}</h1></div>
    <form method="post" action="{{ link }}">
        <input type="hidden" name="{{ option_name }}-sendForm" value="OK" />
        <div class="{{ option_name }}-container">
            {{ GlobalSettingsTable }}
            <hr>
            {{ StyleSettingsTable }}
        </div>
        {{ wp_nonce }}
        {{ submit_button }}
    </form>
    HTML;

	function __construct($title, $slug, $settings) {
        $this->title = $title;
        $this->settings  = $settings; 
        $this->option_name = $slug;
    }

    
    /**
     * Print the page if the rights are grant
     */
    public function render()
    {
        if (!current_user_can('manage_options')) {
            
            wp_die('Non hai i permessi per visualizzare questa pagina');

        } else{

            echo str_replace(
                [ 
                    '{{ title }}',
                    '{{ link }}',
                    '{{ option_name }}',
                    '{{ GlobalSettingsTable }}',
                    '{{ StyleSettingsTable }}',
                    '{{ wp_nonce }}',
                    '{{ submit_button }}'
                ],
                [ 
                    $this->title,
                    esc_html( admin_url( 'admin.php?page='.$_GET["page"] ) ),
                    $this->option_name,
                    ( new GlobalSettingsTable( $this->option_name ))->render(),
                    ( new StyleSettingsTable( $this->option_name."-css"))->render(),
                    wp_nonce_field( 'program-links-options-save', 'blz-affiliation-custom-message' ),
                    get_submit_button()
                ],
                $this->output
            );
            
        }

    }

}