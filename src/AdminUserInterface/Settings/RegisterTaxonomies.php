<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/*
 * Class TaxonomiesManager
 * Author: Blazemedia srl
 *  
 * Initilize the taxonomies of the website
 * 
 */
Class RegisterTaxonomies {
    
    /**
     * Initializer for setting up action handler
     */
    public static function init() {
        add_action('init', [ get_called_class(), 'register_tax' ] );
    }

    public static function register_tax() {

        //TODO we need this taxonomy?
        $posttypes = get_post_types(['public' => 'true']);
        foreach (CONFIG["custom_taxonomies"] as $taxonomy_slug => $taxonomy_name){
            register_taxonomy('blz_Programs', $posttypes, [
                'hierarchical' => false,
                'labels' => $taxonomy_name,
                'show_ui' => true, 'show_in_rest' => false, 'query_var' => false,
                'rewrite' =>  ['slug' => $taxonomy_slug, 'with_front' => false],'public' => false,
                'capabilities' => ['manage_terms' => 'manage_categories','edit_terms' => 'manage_categories','delete_terms' => 'manage_categories','assign_terms' => 'edit_posts'],
            ]);
        }
    }
}


