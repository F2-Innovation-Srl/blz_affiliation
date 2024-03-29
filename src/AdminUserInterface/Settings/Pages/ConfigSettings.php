<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Pages;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Capability;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\ConfigSettingsTable;

/**
 * Pagina delle configurazioni di base del plugin. 
 * Questa dovrebbe essere sempre caricata per default.
 *
 * @package BLZ_AFFILIATION
 */
class ConfigSettings {

    protected $title;
    public $settings;
    protected $option_name;
    protected $json_default = 
    <<<JSON
        {
          "plugin_name"       : "Blazemedia Affiliation",
          "plugin_slug"     : "blz-affiliation",
          "Pages"  : [
              {
                  "name"       : "GA e TrackingID Settings",
                  "slug"       : "blz-affiliation-tracking",
                  "controller" : "GaTrackingIdSettings",
                  "settings" : {
                      "tabs" : [
                          {
                              "name"        : "Editorial Links",
                              "slug"        : "linkButton",
                              "description" : "Gestione dei link editoriali da bottone ",
                              "marketplaces":[
                                      {
                                          "name"              : "Amazon",
                                          "slug"              : "amazon",
                                          "api_slug"          : "amazon",
                                          "description"       : "con prezzo dinamico per Amazon",
                                          "ga_event_template" : "{website} {label} {author} {marketplace} {amp}",
                                          "tracking_id"       : "{website}{label}{author}{marketplace}{amp}-21"
                                      },
                                      {
                                          "name"              : "eBay",
                                          "slug"              : "ebay",
                                          "api_slug"          : "ebay",
                                          "description"       : "con prezzo dinamico per eBay",
                                          "ga_event_template" : "{website} {label} {author} {marketplace} {amp}",
                                          "tracking_id"       : "{website}{label}{author}{marketplace}{amp}"
                                      },
                                      {
                                          "name"              : "Trovaprezzi",
                                          "slug"              : "trovaprezzi",
                                          "api_slug"          : "trovaprezzi",
                                          "description"       : "con prezzo dinamico per Trovaprezzi",
                                          "ga_event_template" : "{website} {label} {author} {marketplace} {amp}",
                                          "tracking_id"       : ""
                                      },
                                      {
                                          "name"              : "Custom",
                                          "slug"              : "custom",
                                          "description"       : "per Custom",
                                          "ga_event_template" : "{website} {label} {author} {marketplace} {amp}",
                                          "tracking_id"       : ""
                                      }
                              ]
                          },
                          {
                              "name"        : "Program Link",
                              "slug"        : "linkPrograms",
                              "description" : "Gestione Program Link e Stored Program Link",
                              "marketplaces":[
                                      {
                                          "name"              : "Generic",
                                          "slug"              : "generic",
                                          "description"       : "",
                                          "ga_event_template" : "{website} {label} {author} {subject} {program} {amp}",
                                          "tracking_id"       : "{website}{label}{author}{subject}{program}{amp}",
                                          "ga_event_template_button" : "{website} {label} cta-button {author} {subject} {program} {amp}",
                                          "tracking_id_button"       : "{website}{label}-cta-button{author}{subject}{program}{amp}"

                                      }
                              ]
                          },{
                              "name"        : "Parse and Render",
                              "slug"      : "parseLinkAndRender",
                              "description" : "Gestione di link automatici ",
                              "marketplaces":[
                                      {
                                          "name"              : "Amazon",
                                          "slug"              : "amazon",
                                          "api_slug"          : "amazon",
                                          "description"       : "Amazon nel content",
                                          "ga_event_template" : "{website} {label} {author} {marketplace} {amp}",
                                          "tracking_id"       : "{website}{label}{author}{marketplace}{amp}-21",
                                          "ga_event_template_button" : "{website} {label} cta-button {author} {marketplace} {amp}",
                                          "tracking_id_button"       : "{website}{label}-cta-button{author}{marketplace}{amp}-21"

                                      },
                                      {
                                          "name"              : "eBay",
                                          "slug"              : "ebay",
                                          "api_slug"          : "ebay",
                                          "description"       : "eBay nel content",
                                          "ga_event_template" : "{website} {label} {author} {marketplace} {amp}",
                                          "tracking_id"       : "{website}{label}{author}{marketplace}{amp}",
                                          "ga_event_template_button" : "{website} {label} cta-button {author} {marketplace} {amp}",
                                          "tracking_id_button"       : "{website}{label}ctabutton{author}{marketplace}{amp}"

                                      }
                              ]
                          },{
                              "name"        : "Tabella",
                              "slug"      : "blz_table",
                              "description" : "Gestione dei link in tabelle di affiliazione",
                              "marketplaces":[
                                      {
                                          "name"              : "Generic",
                                          "slug"              : "generic",
                                          "description"       : "",
                                          "ga_event_template" : "{table-name} {label} {numero-posizione} {marketplace} {amp}",
                                          "tracking_id"       : ""
                                      }
                              ]
                          },
                          {
                              "name"        : "disclaimer",
                              "slug"      : "blz_disclaimer",
                              "description" : "Gestione del disclaimer per casistiche diverse da link inserito tramite plugin",
                              "marketplaces":[
                                      {
                                          "name"              : "Generic",
                                          "slug"              : "generic",
                                          "description"       : "",
                                          "ga_event_template" : "{disclaimer}",
                                          "tracking_id"       : ""
                                      }
                              ]
                          }

                      ]
                      
                  }
              },
              {
                  "name"       : "Program Links Options",
                  "slug"       : "blz-affiliation-program",
                  "controller" : "ProgramLinksOptions"
              },
              {
                "name"       : "Program Links Taxonomies",
                "slug"       : "blz-affiliation-program-taxonomies",
                "controller" : "ProgramLinksTaxonomies"
            },
              {
                  "name"       : "Global Settings",
                  "slug"       : "blz-affiliation-settings",
                  "controller" : "GlobalSettings"
              }
          ]
      }
    JSON;

    private $output =
    <<<HTML
    <div><h1>{{ title }}</h1></div>
    <form method="post" action="{{ link }}">
        <input type="hidden" name="{{ option_name }}-sendForm" value="OK" />
        <div class="{{ option_name }}-container">
            {{ ConfigSettingsTable }}
            <strong>Esempio:</strong><br>
            <pre>{{ code }}</pre>
        </div>
        {{ wp_nonce }}
        {{ submit_button }}
       
    </form>
    HTML;

	function __construct( $is_valid_config, $title, $slug, $settings ) {
        
        $this->is_valid_config     = $is_valid_config;
        $this->title               = $title;
        $this->settings            = $settings; 
        $this->option_name         = $slug;
    }

    
    /**
     * Print the page if the rights are grant
     */
    public function render() {
        
        if( !current_user_can( Capability::AFFILIATION_CAP ) ) {
            
            wp_die('Non hai i permessi per visualizzare questa pagina');
        } 

        /// link alla pagina corrente
        $page_link = esc_html( admin_url( 'admin.php?page='.$_GET["page"] ) );

        /// sarebbe l'oggetto che genera il markup ( in genere si tratta di una tabella )
        $optionSettings = new ConfigSettingsTable( $this->option_name );
        
        /// il markup delle opzioni
        $optionsMarkup = $optionSettings->render();

        /// il campo per validare la form
        $formValidationField = wp_nonce_field( 'program-links-options-save', 'blz-affiliation-custom-message' );



        echo str_replace(
            [ 
                '{{ title }}',
                '{{ link }}',
                '{{ option_name }}',
                '{{ ConfigSettingsTable }}',
                '{{ wp_nonce }}',
                '{{ submit_button }}',
                '{{ code }}'
            ],
            [ 
                $this->title,
                $page_link,
                $this->option_name,
                $optionsMarkup,
                $formValidationField,
                get_submit_button(),
                $this->json_default
            ],
            $this->output
        );

    }

}