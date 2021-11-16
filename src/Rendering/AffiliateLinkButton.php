<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\FileGetContents;
use BLZ_AFFILIATION\Utils\Shortener;


use BLZ_AFFILIATION\AffiliateMarketing\OfferRetriever;

class AffiliateLinkButton {

    private $post;
    private $category;
    private $is_paid;
    private $author;


    public function __construct() {

        /// [ REVIEW ] documentiamo che differenza c'è tra i due

        // Add the shortcode to print the links
        add_shortcode( 'affiliate_track', [ $this, 'printAffiliateTracking'] );

        add_shortcode( 'affiliate_link',  [ $this, 'printAffiliateLink'] );

    }


    /**
     * Stampa il bottone impostato da shortcode
     *     
     */
    public function printAffiliateTracking( $atts, $content, $tag ) {

        /// imposta i valori relativi al post
        $this->setPostData();


        $offers = new OfferRetriever()

        

    }

    /**
     * Stampa il bottone impostato da shortcode
     *     
     */
    public function printAffiliateLink( $atts, $content, $tag ) {
        
        $offerRetriever = new OfferRetriever([
            'Trovaprezzi',
            'Ebay',
            'Amazon',
        ]);

        $offers =  $offerRetriever->getOffers();

        $template = '<a href="{{ DetailPageURL }}" data-vars-affiliate="{{ vars }}" class="affiliation-intext" target="_blank" rel="sponsored">{{ CurrentPrice }}</a>';


        
    }

    /**
     * Imposta i valori relativi al post
     *     
     */
    private function setPostData() {

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

        $this->post = $post;
    }

    /**
     * stampa il link prodotto dallo shortcode
     *
     * @return void
     */
    static function print_affiliate_link( $atts, $content = null ) {

        global $post;
        
        $categories = get_the_category($post->ID);
        
        $category = (isset($categories[0])) ? $categories[0]->slug : "";

        $author_id = $post->post_author;
        $autore = (!empty(get_the_author_meta('user_nicename', $author_id))) ? get_the_author_meta('user_nicename', $author_id) : "author" ;
        $author = (!empty(get_field('analitics_name', 'user_'.$author_id))) ? get_field('analitics_name', 'user_'.$author_id) : $autore;
        
        /// aggiunge paid al marketplace
        $is_paid = has_tag( "paid", $post ) ;

        $output = "";
        $rootNode = "";
        $url = "";
        $tracking = "";

        $class = "mtz cta vg ".$category." editorial " . $author . " " .  $atts["marketplace"];
        $price = "0";
        $template = '<a href="{{DetailPageURL}}" data-vars-affiliate="{{class}}" class="affiliation-intext" target="_blank" rel="sponsored">{{CurrentPrice}}</a>';

    
        switch ($atts["marketplace"]) {
            case "trovaprezzi":
                if (isset($atts["keyword"])) 
                    //$url_trovaprezzi = 'https://quickshop.shoppydoo.it/telefoninoit/'.urlencode($atts["keyword"]).'.aspx?merchantUniqueness=true&resNumCode=TN&sort=popularity&categoryId=7&format=json';
                    $url_trovaprezzi = "https://querydispatcher.justearn.it/api/v1/getoffer/".urlencode($atts["keyword"])."/marketplace/trovaprezziVG/items/1/category/elettronica?min_price=20";     
                
                if (isset($atts["asins"])) 
                    //$url_trovaprezzi = 'https://quickshop.shoppydoo.it/telefoninoit/.aspx?eanCode='.slugify($atts["ean"]);
                    $url_trovaprezzi = "https://querydispatcher.justearn.it/api/v1/getoffer/".urlencode($atts["asins"])."/marketplace/trovaprezziVG/items/1/category/elettronica?code=true&min_price=20";     
                
                                
                $trovaprezzi = FileGetContents::getContent($url_trovaprezzi);
                $array_trovaprezzi = json_decode($trovaprezzi, true);
                
                foreach ($array_trovaprezzi as $OneOffer) {
                    $url = $OneOffer["link"];
                    $price = $OneOffer["price"];
                }

                $tracking = $atts["store"];
                //print_r($price)  ;exit;      
                
                break;
                case "ebay":
                case "ebay_used":
                    if (isset($atts["asins"])) {
                        $searchWithCode ="";
                        // CASO TROVA CODICE DA LINK
                        if (strrpos($atts["asins"],"www.ebay") !== false) {
                            $code = explode("/",$atts["asins"]);
                            $code = explode("?",$code[count($code)-1])[0];
                            $key = $code;
                        }else{
                            // CASO EPID OPPURE EAN INSERITO A MANO
                            if (strrpos($atts["asins"],"EPID") !== false) {
                                $searchWithCode ="code=true&";
                                $key = str_replace("EPID","",$atts["asins"]);
                            }else{
                                $key = $atts["asins"];
                            }
                        }
                    }else{
                        $key= $atts["keyword"];
                    }
                    $marketplace = ($atts["marketplace"] == "ebay") ? "ebayBrowseApi" : "ebay-used";

                    // EBAY //
                    // look for used E-BAY offer  using version's slug and replacing - with +
                    $url_ebay = "https://querydispatcher.justearn.it/api/v1/getoffer/".$key."/marketplace/".$marketplace."/items/1/category/elettronica?".$searchWithCode."min_price=20";
                    $ebay = FileGetContents::getContent($url_ebay);
                    $array_ebay = json_decode($ebay, true);
                    
                    foreach ($array_ebay as $OneOffer) {
                        $url = $OneOffer["link"];
                        $price = $OneOffer["price"];
                        break;
                    }  
                    $tracking = (empty($atts["store"])) ? get_field('ebay_traking_id_editorial', 'user_'.$author_id) : $atts["store"];   
                    if (empty($tracking)) $tracking = "vgClassificheEditorial21";
                    //pre($url_ebay); 
                    //pre($key); 
                    //pre($url);
                    //pre("-----");
                    break;
                default:
                    if (isset($atts["asins"])) {
                        if (strrpos($atts["asins"],"www.amazon") !== false) {
                            $code = explode("/",explode("?",$atts["asins"])[0]);
                            if (strrpos($code[count($code)-1],"ref") !== false) 
                                $code = $code[count($code)-2];
                            else
                                $code = $code[count($code)-1];
                                $key = $code;
                        }else{
                            $key = $atts["asins"];
                            $code = $key; 
                        }
                    }else{
                        $key = $atts["keyword"];
                    }
    
                    $url_amazon = "https://querydispatcher.justearn.it/api/v1/getoffer/".$key."/marketplace/amazon/items/1/category/elettronica?min_price=20".(($code) ? "&code=true": "");
                    $amazon = FileGetContents::getContent($url_amazon);
                    $payload = json_decode($amazon, true);
                    
                    $tracking = (empty($atts["store"])) ? get_field('amazon_traking_id_editorial', 'user_'.$author_id) : $atts["store"];   
                    if (empty($tracking)) $tracking = "vg-classifiche-editorial-21";


                    foreach ($payload as $OneOffer) {
                        $url = $OneOffer["link"];
                        $price = $OneOffer["price"];
                        break;
                    }  


                    
            }

            
        $text = (isset($atts["text"])) ? urldecode($atts["text"]) : $price . " euro";
        $template = str_replace("{{CurrentPrice}}",$text,$template);

        
        
        //GA TRACKING
        $template = str_replace("{{class}}",$class,$template);
        //MARKETPLACE TRACKING
        $url = str_replace(["{CUSTOM_ID}","{tracking-id}"],$tracking,$url);

        if ($atts["marketplace"] != "ebay")
            $url = Shortener::generateShortLink($url);
        $template = str_replace("{{DetailPageURL}}",$url,$template);
       

        $output = ($price != "0") ? $template : "";


        return $output; // do_shortcode allows for nested Shortcodes

    }

    

    /**
     * stampa il link prodotto dallo shortcode
     *
     * @return void
     */
    static function print_affiliate_tracking($atts, $content = null) {

        //$class = isset($atts["class"]) ? ' class="'.$atts["class"].'" ' : '';
        $class=" class='affiliation-intext'";
        $output = '<a href="'.$atts["url"].'" data-vars-affiliate="'.$atts["data-affiliate"].'"'.$class.' target="_blank" rel="sponsored">'.$atts["text"].'</a>';

        // perché salva in globals?
        $GLOBALS["data_affiliates"][] = $atts["data-affiliate"];

        return $output; // do_shortcode allows for nested Shortcodes
    }

}
