<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/**
 * Inizializza il ruolo BlzAffiliationUser con capability 'edit_blz_affiliation'
 * ed assegna la stessa capability ancha ad administrator
 */
class Capability {

    private static $instance;

    const AFFILIATION_ROLE_NAME = 'Affiliation Manager';
    const AFFILIATION_ROLE      = 'affiliation_manager';
    const AFFILIATION_CAP       = 'edit_blz_affiliation';


    private $roles;

    public $author_capabilities;

    private function __construct() {

        /// crea il ruolo di Affiliation Manager
        $this->createAffiliationMgrRole();

        $this->roles = wp_roles();

        $this->assignCapToRoles( [ 'administrator', Capability::AFFILIATION_ROLE ]);

        $this->setAuthorCaps();
        
    }

    public function assignCapToRoles( array $roles ) {

        foreach( $roles as $role ) {

            $cur_role = $this->roles->role_objects[ $role ];
            $cur_role->add_cap( Capability::AFFILIATION_CAP );
        }        
    }

    private function createAffiliationMgrRole() {

        add_role(
            
            Capability::AFFILIATION_ROLE,  // System name of the role.        
            __(  Capability::AFFILIATION_ROLE_NAME  ),  // Display name of the role.
            [
                // generiche
                'read'                   => true,
                'delete_posts'           => true,
                'delete_published_posts' => true,
                'edit_posts'             => true,
                'publish_posts'          => true,
                'upload_files'           => true,
                
                'manage_links'      => true,
                "unfiltered_html"   => true,

                /// pages
                'edit_pages'             => false,
                'edit_published_pages'   => false,
                'publish_pages'          => false,
                'delete_published_pages' => false, // This user will NOT be able to  delete published pages.
                'delete_others_pages'    => false,
            
          
                /// da editor
                'edit_others_posts'             => true,
                'delete_others_posts'           => true,
                'wpseo_bulk_edit'               => true,
                'wpseo_manage_redirects'        => true,
                'post_pay_counter_access_stats' => true,


                /// altre                
                "level_7" => true,
                "level_6" => true,
                "level_5" => true,
                "level_4" => true,
                "level_3" => true,
                "level_2" => true,
                "level_1" => true,
                "level_0" => true,
      

                'delete_private_posts'  => false,
                'edit_private_posts'   => false,
                'read_private_posts' => false,
                'delete_private_pages' => false,
                'edit_private_pages' => false,
                'read_private_pages' => false,
                'read_private_nav_menu_items' => false,
                'edit_nav_menu_items' => false,
                'edit_others_nav_menu_items' => false,
                'edit_private_nav_menu_items' => false,
                'edit_published_nav_menu_items' => false,
                'delete_nav_menu_items' => false,
                'delete_others_nav_menu_items' => false,
                'delete_private_nav_menu_items' => false,
                'delete_published_nav_menu_items' => false,
                'publish_nav_menu_items' => false,

                'manage_categories' => false,
                'moderate_comments' => false,                
            ]
        );
    }

    private function setAuthorCaps() {

        /// il campo serializzato con le impostazioni di base del plugin
        $config_option = get_option( "blz-affiliation-basic" );

        /// imposta le capabilities per gli autori
        $caps_exists = is_array( $config_option ) && !empty( $config_option['author_capabilities'] );

        /// esiste una config option valida 
        $this->author_capabilities = $caps_exists ? explode( ',', $config_option['author_capabilities'] ) : ['edit_posts'];

    }

    /**
     * Array delle capabilities per accedere al plugin
     *
     * @return array
     */
    public static function isAuthorEnabled() {

        return !empty( array_filter( self::$instance->author_capabilities, function( $capability) {

            return current_user_can( $capability );
        }));     
    }

    
    /**
     * Metodo pubblico per l'accesso all'istanza unica di classe.
     * @return object|
     */
    public static function getInstance() {

        if ( !isset( self::$instance ) ) {
            
            self::$instance = new Capability();
        }
        
        return self::$instance;
    }

}