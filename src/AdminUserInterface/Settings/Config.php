<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Pages\Page;

class Config {

    /**
     * Istanza unica del singleton
     * @var object
     */
    private static $instance;
    
    /// esiste una config option valida
    public $is_valid;

    public  $pages;
    public  $plugin_name;
    public  $plugin_slug;
    public  $is_affiliation_page;
    
    private function __construct() {

        $this->is_affiliation_page = "false";
        
        /// prende la conf minima 
        $config_file = file_get_contents( BLZ_AFFILIATION_PATH . 'config.json' );

        $basic_config = empty( $config_file ) ? [] : json_decode( $config_file, true );

        /// il campo serializzato con le impostazioni di base del plugin
        $config_option = get_option( "blz-affiliation-basic" );

        /// esiste una config option valida 
        $this->is_valid = is_array( $config_option ) && !empty( $config_option['config'] );

        $config_json = $this->is_valid ? stripslashes( $config_option['config'] ) : '';

        $config = json_decode( $config_json, true );

        /// crea l'array associativo dal JSON
        if( empty( $config ) ) {

            $config = $basic_config;

        }  else {

            $config[ 'Pages' ] = array_merge( $config[ 'Pages' ], $basic_config ['Pages'] );
        }
    
        
        $this->plugin_name = $config["plugin_name"];
        $this->plugin_slug = $config["plugin_slug"];
        
        /// setup delle pagine
        $this->pages = array_map( function( $page ) { 
            
            /// prende la classe che dovrà gestire la pagina
            $controller = "BLZ_AFFILIATION\\AdminUserInterface\\Settings\\Pages\\". $page[ "controller" ];
            
            /// raccoglie eventuali impostazioni specifiche della pagina
            $settings = isset( $page[ "settings" ] ) ? $page["settings"] : null;

            /// crea la pagina 
            return new Page([
                "name"       => $page[ "name" ],
                "slug"       => $page[ "slug" ],
                "controller" => new $controller( $this->is_valid, $page[ "name" ], $page[ "slug" ], $settings )
            ]);

        }, $config[ "Pages" ] );
    }

    /**
     * Metodo pubblico per l'accesso all'istanza unica di classe.
     * @return object|Config
     */
    public static function loadSettings() {

        if ( !isset( self::$instance ) ) {
            
            self::$instance = new Config();
        }
        
        return self::$instance;
    }

}
