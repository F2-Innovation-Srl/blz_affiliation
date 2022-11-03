<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/**
 * Post type per i quali è abilitato il plugin
 */
class PostTypes {

    private static $instance;

    private $post_types;

    private function __construct() {

        $this->post_types = $this->getPostTypes();
    }


    /**
     * ritorna l'array dei post types che sono 
     * abilitati al plugin
     * 
     * se ritorna l'insieme vuoto, sono abilitati tutti i post-type
     *
     * @return array
     */
    private function getPostTypes() {

        /// il campo serializzato con le impostazioni di base del plugin
        $config_option = get_option( "blz-affiliation-basic" );

        /// rileva l'esistenza di una impostazione per i post-types
        $post_types_exists = is_array( $config_option ) && !empty( $config_option['post_types'] );

        /// esiste una config option valida 
        return $post_types_exists ? explode( ',', $config_option['post_types'] ) : [];
    }


    /**
     * Verifica che il post type corrente sia valido 
     *
     * @return array
     */
    public static function isPostTypeEnabled( string $post_type = '' ) {

        if( empty( self::$instance->post_types ) ) return true;

        if( empty( $post_type ) ) $post_type = get_post_type();

        return in_array( $post_type, self::$instance->post_types );
    }

    
    /**
     * Metodo pubblico per l'accesso all'istanza unica di classe.
     * @return object|
     */
    public static function getInstance() {

        if ( !isset( self::$instance ) ) {
            
            self::$instance = new PostTypes();
        }
        
        return self::$instance;
    }

}