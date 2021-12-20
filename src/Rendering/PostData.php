<?php

namespace BLZ_AFFILIATION\Rendering;


class PostData {


    /**
     * Istanza unica del singleton
     * @var object
     */
    private static object $instance;
 

    public static $post_type;
    public static $tags;
    public static $is_amp;
    public static $author;
    public static $taxonomies;

    public function __construct() {
        add_action( 'wp', [ get_called_class(), 'loadData' ] );
    }

    /**
     * Metodo pubblico per l'accesso all'istanza unica di classe.
     * @return object|PostData
     */
    public static function getInstance() {
        if ( !isset(self::$instance) ) {
            self::$instance = new PostData();
        }
        return self::$instance;
    }

    public static function loadData() {

        global $post;   

        /// post type
        self::$post_type = $post->post_type;
        
        /// Author
        /// cerca il nome dell'autore
        $author_nicename = get_the_author_meta( 'user_nicename', $post->post_author);
        /// se è vuoto prende un valore di default
        $author_name    = empty( $author_nicename ) ? 'author' : $author_nicename;  // autore

        self::$author = [
            'name'      => $author_name,
            'id'        => $post->post_author
        ];

        /// tassonomie 
        $taxonomies = get_taxonomies();
        foreach( $taxonomies as $taxonomy) 
            foreach( get_the_terms( $post->ID, $taxonomy ) as $tax)
                self::$taxonomies[$taxonomy][] = $tax->slug;
        
        
        /// aggiunge se è anmp
        self::$is_amp = (is_amp_endpoint()) ? "true" : "false";
     
    }


}
