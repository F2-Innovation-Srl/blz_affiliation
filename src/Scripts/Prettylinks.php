<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
setlocale(LC_TIME, 'it_IT');
gc_enable();
define('WP_USE_THEMES', true);
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
require '../wp/wp-load.php';
require_once '../wp/wp-admin/includes/taxonomy.php';

class Prettylinks {

    private $post;
    private $output;
    private $CSV;
    /// il pattern da riconoscere nel testo da ridefinire
    protected $pattern = '/<a.*?href="(https:\/\/.*?mtz-editorial.*?)".*?>(.*?)<\/a>/';

    /// viene richiamata dal costruttore
    public function __construct() {
        
        if ($_GET["type"] == "ExportCSV"){
            $this->GenerateCSV();
        }
        if ($_GET["type"] == "ImportCSV"){
            $this->ImportCSV();
        }
    }

    private function ImportCSV(){
        $this->CSV = [];
        if (($handle = fopen("VG.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $num = count($data);
                $row++;
                if (!empty($data[0]))
                    $this->CSV[$data[0]][] = [ 
                        "old" => $data[4],
                        "new" => $data[7]
                    ];
                
            }
            fclose($handle);
        }
        
        $this->findLinksInDB();
        $this->replaceLinks();
    }

    private function GenerateCSV(){
        $this->findLinksInDB();
        $this->printOutput();
        
    }
    private function findLinksInDB(){
        global $wpdb;
        $posts = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_status = 'publish' AND post_content LIKE '%mtz-editorial%'" );
        //$posts = new \WP_Query(['post_status' => 'publish','s' => 'mtz-editorial', 'post_type' => ['post']]);
        foreach( $posts as $post )
            $this->Parse($post);
    }
    /// viene richiamata dal costruttore
    private function Parse($post) {
        $this->post = $post;
        preg_match_all( $this->pattern, $this->post->post_content, $matches );

        return array_map( function( $link, $url, $text ) {

            /// per indicare il  marketplace si usa il prefisso '--'
            $marketplace = preg_filter( '/.*?--(.*)/', '$1', $url );

            /// se ha con se il tracking id
            //  lo separiamo dalla url
            //$url = ( $marketplace ) ? preg_filter( '/(.*?)--.*/', '$1', $url ) : $url;
            $this->$output[] = [
                'link'        => $link,
                'url'         => $url,
                'text'        => $text,
                'marketplace' => ( $marketplace ) ? $marketplace : '',
                'post_data'   => $this->loadPostData()
            ];

        }, $matches[0], $matches[1], $matches[2] );
    }

    private function loadPostData() {

        $obj = [];
        $obj["ID"] = $this->post->ID;
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

    private function replaceLinks(){
        $newOutputs = [];
        foreach ($this->$output as $row) 
        {
            $newShortcode = $this->findLink($row['post_data']["ID"],$row['url']). $row['text'] . "[/affiliate_program_link]";
            echo " UPDATE wp_posts set post_content = REPLACE(post_content, '".addslashes($row['link'])."', '".addslashes($newShortcode)."') where ID = ".$row['post_data']["ID"].";\r\n";
            $newOutputs[] = [
                "ID" => $row['post_data']["ID"],
                "link" => $row['link'],
                "new"  => $newShortcode
            ];
        }   
       
    } 

    function findLink($ID,$link){
            foreach ($this->CSV[$ID] as $row)
                if (trim($row["old"]) == trim($link))
                    return $row["new"];   
                    
    }

    function printOutput(){
        echo "<table>";
        echo '<tr>';
        echo '<td><strong>ID</strong></td>';
        echo '<td><strong>POST TYPE</strong></td>';
        echo '<td><strong>AUTORE</strong></td>';
        echo '<td><strong>TAXONOMY</strong></td>';
        echo '<td><strong>PRETTY LINK</strong></td>';
        echo '<td><strong>URL</strong></td>';
        echo '<td><strong>MARKETPLACE</strong></td>';
        echo '</tr>';
        foreach ($this->$output as $row) 
        { 
            
            echo '<tr>';
            echo '<td>' . $row['post_data']["ID"] . '</td>';
            echo '<td>' . $row['post_data']["post_type"] . '</td>';
            echo '<td>' . $row['post_data']["author"]["name"] . '</td>';
            echo '<td>';
            foreach ($row['post_data']["taxonomies"] as $key => $val)
                    echo "<strong>" .$key . "</strong> : " .implode(",",$val). " --- ";
            echo '</td>';
            echo '<td><xmp>' . $row['link'] . '</xmp></td>';
            echo '<td>' . $row['url'] . '</td>';
            echo '<td>' . $row['marketplace'] . '</td>';
            echo '</tr>';
        }
        echo "</table>";
    }
}



new Prettylinks();
?>