<?php

namespace BLZ_AFFILIATION\Rendering;


class PostData {

    public $post_type;
    public $tags;
    public $is_amp;
    public $author;

    public function __construct() {

        global $post;   

        /// post type
        $this->post_type = $post->post_type;
        
        /// Author
        $this->author = $post->post_author;

        /// tags
        foreach( get_the_tags( $post->ID ) as $tag)
            $this->tags[] = $tag->slug;

        /// tassonomie 
        $taxonomies = get_taxonomies();
        foreach( $taxonomies as $taxonomy) 
            foreach( get_the_terms( $post->ID, $taxonomy ) as $tax)
                $this->taxonomies[$taxonomy][] = $tax->slug;
        
        
        /// aggiunge se è anmp
        $this->is_amp = (is_amp_endpoint()) ? "true" : "false";

    }


}
