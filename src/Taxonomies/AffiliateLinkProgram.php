<?php
namespace BLZ_AFFILIATION\Taxonomies;

/*
 * Class TaxonomiesManager
 * Author: Blazemedia srl
 *  
 * Initilize the taxonomies of the website
 * 
 */
class AffiliateLinkProgram {
    
    public static $taxonomies = [ 
        'blz-affiliation-page-type'  => "Tipo Pagina",
        'blz-affiliation-programs'   => 'Programs',
        'blz-affiliation-platform'   => 'Piattaforma',
        'blz-affiliation-type'       => 'Tipo link' 
    ];
    /**
     * Initializer for setting up action handler
     */
    public static function init() {
        
        add_action( 'init',                                     [ get_called_class(), 'register_tax'                     ] );
        add_action( 'save_post_program_stored_link',            [ get_called_class(), 'save_tax'                         ], 20, 3 );
        add_action( 'restrict_manage_posts',                    [ get_called_class(), 'tsm_filter_post_type_by_taxonomy' ] );        
        add_action( 'manage_program_stored_link_posts_columns', [ get_called_class(), 'add_column_to_listing_list'       ] );        
        add_action( 'manage_posts_custom_column',               [ get_called_class(), 'show_column_for_listing_list'     ], 10, 2 );
        //add_action( 'manage_edit-program_stored_link_sortable_columns', [ get_called_class(), 'add_column_to_listing_list']);

        add_filter( 'parse_query',                              [ get_called_class(), 'tsm_convert_id_to_term_in_query'  ] );

    }

    /**
     * hook che scatta quando viene salvato un post di
     * tipo program stored link
     *
     * @param int $post_id
     * @param WP_Post $post
     * @param array $update
     * @return void
     */
    public static function save_tax( $post_id, $post, $update ) {

        if( $update ) {

            /// rimuove dal post tutte le tassonomie
            foreach( self::$taxonomies as $taxonomy_slug => $taxonomy_name) {

                wp_delete_object_term_relationships( $post_id, $taxonomy_slug );
            }
        }
            
        /// prende lo shortcode
        $attr = shortcode_parse_atts( $post->post_content );

        foreach( self::$taxonomies as $taxonomy_slug => $taxonomy_name ) {
            
            $key = str_replace( "programs", "program", str_replace( "blz-affiliation-", "", $taxonomy_slug ) );
            
            if( isset( $attr[ $key ] ) ) {
                
                $term = get_term_by( 'slug', $attr[ $key ], $taxonomy_slug );
                
                if( isset( $term->term_id ) ) {

                    wp_set_object_terms( $post_id, [ $term->term_id, $term->parent ], $taxonomy_slug );
                } 
            }
        }  
    }


    public static function register_tax() {
        
        // TODO - do we need this taxonomy?
        
        $posttypes = get_post_types( ['public' => 'true'] );

        foreach ( self::$taxonomies as $taxonomy_slug => $taxonomy_name){
            
            register_taxonomy( $taxonomy_slug, ['post','program_stored_link'], [
                'hierarchical' => true,
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
                'query_var'         => true,
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

    /**
     * Display a custom taxonomy dropdown in admin
     * @author Mike Hemberger
     * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
     */
    public static function tsm_filter_post_type_by_taxonomy() {

        global $typenow;

        $post_type = 'program_stored_link'; // change to your post type

        foreach ( self::$taxonomies as $taxonomy => $taxonomy_name) {

            if( $typenow == $post_type ) {
                
                $selected      = isset( $_GET[ $taxonomy ]) ? $_GET[ $taxonomy ] : '';
                
                $info_taxonomy = get_taxonomy( $taxonomy );
                
                wp_dropdown_categories( [
                    
                    'show_option_all' => sprintf( __( 'Tutti i %s', 'textdomain' ), $info_taxonomy->label ),
                    'taxonomy'        => $taxonomy,
                    'name'            => $taxonomy,
                    'orderby'         => 'name',
                    'selected'        => $selected,
                    'show_count'      => true,
                    'hide_empty'      => false,
                    'hierarchical'    => true
                ] );
            };
        }
    }


    /**
     * Filter posts by taxonomy in admin
     * @author  Mike Hemberger
     * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
     */
    public static function tsm_convert_id_to_term_in_query( $query ) {
        
        global $pagenow;
        
        $post_type = 'program_stored_link'; // change to your post type
        
        foreach ( self::$taxonomies as $taxonomy => $taxonomy_name) {
            
            $q_vars    = &$query->query_vars;
            
            if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type  && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {

                $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
                $q_vars[$taxonomy] = $term->slug;
            }
        }
    }


    public static function add_column_to_listing_list( $posts_columns ) {
        $new_posts_columns = [];
        $new_posts_columns["cb"] = "<input type=\"checkbox\" />";
        $new_posts_columns["title"] = "Titolo";
        foreach ( self::$taxonomies as $taxonomy => $taxonomy_name){
            if($taxonomy == "blz-affiliation-programs" ){
                $new_posts_columns[$taxonomy."-parent"] = "Subject";
            }
            $new_posts_columns[$taxonomy] = $taxonomy_name;
            
        }
        return $new_posts_columns;
    }


    public static function show_column_for_listing_list( $column_id,$post_id ) {
        
        global $typenow;
        if ($typenow=='program_stored_link') {
             
            $terms = self::sort_terms(get_the_terms($post_id,str_replace("-parent","",$column_id)));
            if (is_array($terms)) {
                foreach($terms as $key => $term) 
                    if ($term->parent == 0 && (strpos($column_id,"-parent") !== false) || (strpos($column_id,"-parent") === false)) {
                        echo  '<a href="'.self::createLink($term).'">' . $term->slug. '</a>';
                        return;
                    }
                        
                  
            }     
           
        }
    }

    private static function sort_terms ( $terms ) {
        if (is_array($terms) )
            usort($terms, function($a, $b) {return strcmp($b->parent, $a->parent);});
        return $terms;
    }


    private static function createLink( $term ) {

        $link = "/wp/wp-admin/edit.php?post_type=program_stored_link";

        foreach ( self::$taxonomies as $taxonomy_slug => $taxonomy_name) {

            if ( isset( $_GET[$taxonomy_slug] ) || ( $term->taxonomy == $taxonomy_slug ) ) {

                $link .= "&".$taxonomy_slug."=" . (($term->taxonomy == $taxonomy_slug) ? $term->term_id : $_GET[$taxonomy_slug]);
            }                
        }
            
        return $link;
    }
}