<?php

namespace BLZ_AFFILIATION\Rendering;


class PostData {

    /**
     * Istanza unica del singleton
     * @var object
     */
    private static $instance;
 

    public  $post_type;
    public  $tags;
    public  $is_amp;
    public  $author;
    public  $taxonomies;

    private function __construct() {
        $this->loadData();
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

    private function loadData() {

        global $post;   

        /// post type
        $this->post_type = $post->post_type;
        
        /// Author
        /// cerca il nome dell'autore
        $author_nicename = get_the_author_meta( 'user_nicename', $post->post_author);

        /// se è vuoto prende un valore di default
        $author_name    = empty( $author_nicename ) ? 'author' : $author_nicename;  // autore

        $this->author = [
            'name'      => $author_name,
            'id'        => $post->post_author
        ];


        /// tassonomie 
        $taxonomies = get_taxonomies();

        foreach( array_values($taxonomies) as $taxonomy ) {

            $terms = get_the_terms( $post->ID, $taxonomy );

            if( !empty( $terms ) ){
                $this->taxonomies[ $taxonomy ] = array_map( function( $tax ) {

                    return $tax->slug;    
                }, $terms);    
            }            
        }
        $is_amp = (function_exists('is_amp_endpoint')) is_amp_endpoint() : false;
        /// aggiunge se è amp
        $this->is_amp = ($is_amp) ? "true" : "false";
     
    }
}