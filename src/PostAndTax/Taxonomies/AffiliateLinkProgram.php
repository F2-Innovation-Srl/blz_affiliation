<?php
namespace BLZ_AFFILIATION\PostAndTax\Taxonomies;

/*
 * Class TaxonomiesManager
 * Author: Blazemedia srl
 *  
 * Initilize the taxonomies of the website
 * 
 */
class AffiliateLinkProgram {
    
    /**
     * Initializer for setting up action handler
     */
    public static function init() {
        add_action('init', [ get_called_class(), 'register_tax' ] );
    }

    


    public static function register_tax() {

        $taxonomies = [ 
            'blz-affiliation-programs'=> 'Programs',
            'blz-affiliation-subject' => 'Subject'
        ];

        //TODO we need this taxonomy?
        $posttypes = get_post_types(['public' => 'true']);
        foreach ( $taxonomies as $taxonomy_slug => $taxonomy_name){
            
            register_taxonomy( $taxonomy_slug, ['post'], [
                'hierarchical' => false,
                'labels' => [
                    'name'          => $taxonomy_name,
                    'singular_name' => $taxonomy_name,
                    'search_items'  => 'Cerca tra  '.$taxonomy_name,
                    'all_items'     => 'Tutti '.$taxonomy_name,
                    'edit_item'     => 'Modifica '.$taxonomy_name,
                    'update_item'   => 'Aggiorna '.$taxonomy_name,
                    'add_new_item'  => 'Aggiungi '.$taxonomy_name,
                    'new_item_name' => 'Nuovo '.$taxonomy_name,
                    'menu_name'     => $taxonomy_name,
                ],
                'show_ui'           => false,
                'show_admin_column' => false,
                'query_var'         => false,
                'rewrite'           => false,
                'show_in_rest'      => false,
                'public'            => false,
                'capabilities'      => [
                    'manage_terms' => 'manage_categories',
                    'edit_terms'   => 'manage_categories',
                    'delete_terms' => 'manage_categories',
                    'assign_terms' => 'edit_posts',
                ]
            ]);

        }
    }
}


