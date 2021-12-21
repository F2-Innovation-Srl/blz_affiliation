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

    public $settings;
    protected $option_name;
    
    private $output =
    <<<HTML
    <form method="post" action="{{ link }}">
        <input type="hidden" name="{{ option_name }}-sendForm" value="OK" />
        <div class="{{ option_name }}-container">
            <h2>Global Settings</h2>
            {{ GlobalSettingsTable }}
            <hr>
            <h3>Link style</h3>
            {{ StyleSettingsTable }}
        </div>
        {{ wp_nonce }}
        {{ submit_button }}
    </form>
    HTML;

	function __construct($slug, $settings) {

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
                    '{{ link }}',
                    '{{ option_name }}',
                    '{{ GlobalSettingsTable }}',
                    '{{ StyleSettingsTable }}',
                    '{{ wp_nonce }}',
                    '{{ submit_button }}'
                ],
                [ 
                    esc_html( admin_url( 'admin.php?page='.$_GET["page"] ) ),
                    $this->option_name,
                    ( new GlobalSettingsTable( $this->option_name ))->render(),
                    ( new StyleSettingsTable( $this->option_name ))->render(),
                    wp_nonce_field( 'program-links-options-save', 'blz-affiliation-custom-message' ),
                    get_submit_button()
                ],
                $this->output
            );
            
        }

    }

}