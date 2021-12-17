<?php

namespace BLZ_AFFILIATION\Rendering;


class PostData {

    public $post_type;
    public $tags;
    public $is_amp;
    public $author;

    public function __construct() {
        add_action( 'init', [ $this, 'loadData' ] );
    }

    function loadData() {

        global $post;   

        /// post type
        $this->post_type = $post->post_type;
        
        /// Author
        /// cerca il nome dell'autore
        $author_nicename = get_the_author_meta( 'user_nicename', $post->post_author);
        /// se è vuoto prende un valore di default
        $author_name    = empty( $author_nicename ) ? 'author'     : $author_nicename;  // autore

        $this->author = [
            'name'      => $author_name,
            'id'        => $post->post_author
        ];

        /// tassonomie 
        $taxonomies = get_taxonomies();
        foreach( $taxonomies as $taxonomy) 
            foreach( get_the_terms( $post->ID, $taxonomy ) as $tax)
                $this->taxonomies[$taxonomy][] = $tax->slug;
        
        
        /// aggiunge se è anmp
        $this->is_amp = (is_amp_endpoint()) ? "true" : "false";

        define('POST_DATA', (array) $this);
    }


}
