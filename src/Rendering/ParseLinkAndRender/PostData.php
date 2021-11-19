<?php

namespace BLZ_AFFILIATION\Rendering\ParseLinkAndRender;



class PostData {


    private $post;
    public $category;
    public $is_paid;
    public $author;

    
    public function __construct() {

        global $post;    

        $this->marketPlace = "amazon";
        if ( has_tag( "ebay" ) )        $this->marketPlace = "ebay";
        if ( has_tag( "trovaprezzi" ) ) $this->marketPlace = "trovaprezzi";
        
        
        $categories = get_the_category($post->ID);
        $author_id = $post->post_author;

        /// cerca il nome dell'autore
        $author_nicename = get_the_author_meta( 'user_nicename', $author_id);

        /// cerca il custom field 'analitics_name' associato all'utente 
        $analytics_name = get_field( 'analitics_name', 'user_' . $author_id );


        $author_name    = empty( $author_nicename ) ? 'author'     : $author_nicename;  // autore
        $analytics_name = empty( $analytics_name  ) ? $author_name : $analytics_name;   // author

        /// se è vuoto prende un valore di default
        $this->author = (object) [
            'name'      => $author_name,
            'analytics' => $analytics_name
        ];

        /// categoria
        $this->category = isset( $categories[0] ) ? $categories[ 0 ]->slug : "";
        
        /// aggiunge paid al marketplace
        $this->is_paid = has_tag( "paid", $post ) ;

        $this->marketPlace .= $this->is_paid ? "-paid" : "";

        $this->is_amp = is_amp_endpoint();
        
        $this->post = $post;
     
    }

    public function ParseAndRender( $content ) {

        /// se non è una single non fa nulla
        if ( !is_singular() ) return $content;
    }

}
