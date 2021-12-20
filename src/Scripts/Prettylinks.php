<?php
namespace BLZ_AFFILIATION\Scripts;

use  BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Link;

class Prettylinks {

    private $post;
    private $output;

    /// il pattern da riconoscere nel testo da ridefinire
    protected $pattern = '/<a.*?href="(https:\/\/.*?mtz-editorial.*?)".*?>/';

    /// viene richiamata dal costruttore
    public function __construct() {
        
        // per compatibilità shell
        if (isset($argv)) {
            parse_str(
                join(
                    '&',
                    array_slice($argv, 1)
            ),
                $_GET
            );
        }

        if ($_GET["type"] == "ExportCSV"){
            $this->GenerateCSV();
        }
        
    }

    private function GenerateCSV(){
        $posts = new WP_Query(['post_status' => 'publish','post_type' => ['post']]);
        foreach( $posts->get_posts() as $post )
            foreach( $this->Parse($post) as $linkData ) {

            }
    }

    /// viene richiamata dal costruttore
    private function Parse($post) {
        $this->post = $post;

        preg_match_all( $this->pattern, $this->post->post_content, $matches );

        return array_map( function( $link, $url ) {

            /// per indicare il  marketplace si usa il prefisso '--'
            $marketplace = preg_filter( '/.*?--(.*)/', '$1', $url );

            /// se ha con se il tracking id
            //  lo separiamo dalla url
            $url = ( $marketplace ) ? preg_filter( '/(.*?)--.*/', '$1', $url ) : $url;

            return new Link ([
                'old_link'    => $link,
                'url'         => $url,
                'marketplace' => ( $marketplace ) ? $marketplace : ''
            ]);

        }, $matches[0], $matches[1] );
    }

    private function loadPostData() {

        $obj = [];
        /// post type
        $obj["post_type"] = $this->post->post_type;
        
        /// Author
        /// cerca il nome dell'autore
        $author_nicename = get_the_author_meta( 'user_nicename', $this->post->post_author);
        /// se è vuoto prende un valore di default
        $author_name    = empty( $author_nicename ) ? 'author'     : $author_nicename;  // autore

        $obj["author"] = [
            'name'      => $author_name,
            'id'        => $this->post->post_author
        ];

        /// tassonomie 
        $taxonomies = get_taxonomies();
        foreach( $taxonomies as $taxonomy) 
            foreach( get_the_terms( $this->post->ID, $taxonomy ) as $tax)
            $obj["taxonomies"][$taxonomy][] = $tax->slug;
        
        
        return $obj;
    }
}


new CSVPrettylinkList();
?>