<?php

namespace BLZ_AFFILIATION\Rendering\ParseLinkAndRender;


class PostData {

    public $category;
    public $is_paid;
    public $is_amp;
    public $author;
    public $website;

    /// elenco dei marketplace da shortlinkare
    public $shortables = [
        'amazon',
        'trovaprezzi'
    ];
    
    public function __construct() {

        global $post;    

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

        $this->is_amp = is_amp_endpoint();

        $this->website = 'stub website';
    }


}
