<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Shortener;

use BLZ_AFFILIATION\Utils\HttpRequest;
use BLZ_AFFILIATION\AffiliateMarketing\Offer;




use BLZ_AFFILIATION\AffiliateMarketing\OfferRetriever;
use BLZ_AFFILIATION\AffiliateMarketing\OffersRetriever;
use BLZ_AFFILIATION\AffiliateMarketing\Request;

class AffiliateLinkButton {

    private $post;
    private $category;
    private $is_paid;
    private $author;

    private $templates = [
        
        'affiliate_link' => <<<HTML

            <a href="{{ url }}" data-vars-affiliate="{{ ga_event }}" 
               class="affiliation-intext" target="_blank" rel="sponsored"
            >{{ content }}</a>
        HTML,

        'editorial_link' => <<<HTML

            <a href="{{ url }}" data-vars-affiliate="{{ ga_event }}" 
               class="affiliation-intext" target="_blank" rel="sponsored"
            >{{ content }}</a>
        HTML,

        'ga_event' => <<<EVT
            mtz cta {{ website }} {{ category }} editorial {{ author }} {{ marketplace }}
        EVT
    ];


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
        //$this->setPostData();


       // $offers = new OfferRetriever();

        //$class = isset($atts["class"]) ? ' class="'.$atts["class"].'" ' : '';
        $class=" class='affiliation-intext'";
        $output = '<a href="'.$atts["url"].'" data-vars-affiliate="'.$atts["data-affiliate"].'"'.$class.' target="_blank" rel="sponsored">'.$atts["text"].'</a>';

        // perché salva in globals?
        $GLOBALS["data_affiliates"][] = $atts["data-affiliate"];

        return $output; // do_shortcode allows for nested Shortcodes

    }


    private function FillTemplate( Offer $offer, $ga_event, $tracking, $template) {

        $link = str_replace( '{tracking-id}', $tracking, $offer->link);

        return str_replace([ '{{ url }}', '{{ ga-event }}', '{{ content }}' ], [ $link, $ga_event, $offer->price ], $template);
    }

    
    private function getTracking( Offer $offer, $store ) {

        $class = "mtz cta vg ".$this->category." editorial " . $author . " " .  $atts["marketplace"];
        
        
        $ga_event = str_replace(
            [ '{{ website }}', '{{ category }}', '{{ author }}', '{{ marketplace }}'],
            [ $this->domain, $this->category, $this->author, $offer->marketplace . $this->paid ],
            $this->templates['ga_event']
        );

        switch( $offer->marketplace ) {

            case 'trovaprezzi':
                $tracking = $store;
                break;

            case 'ebay':
                $tracking = (empty($store)) ? get_field('ebay_traking_id_editorial', 'user_'.$author_id) : $store;   
                if (empty($tracking)) $tracking = "vgClassificheEditorial21";
                break;

            case 'amazon':
                $tracking = (empty($store)) ? get_field('amazon_traking_id_editorial', 'user_'.$author_id) : $store;   
                if (empty($tracking)) $tracking = "vg-classifiche-editorial-21";
                break;
        }


        return (object) [
            'ga_event'    => $ga_event,
            'tracking_id' => $tracking
        ];
    }


    /**
     * Stampa il bottone impostato da shortcode
     *     
     */
    public function printAffiliateLink( $atts, $content, $tag ) {

        /// prende tutti i dati del post
        $this->setPostData();
        
        /// cerca le offerte nei tre marketplace
        /// effettua una chiamata a querydispatcher 
        /// per ogni marketplace        
        $offerRetriever = new OffersRetriever( new Request( $atts ), [
            'Trovaprezzi',
            'Ebay',
            'Amazon',
        ]  );

        /// riceve le offerte in ordine di marketplace
        $offers = $offerRetriever->getOffers();

        if( !empty( $offers ) ){

            $tracking = $this->getTracking( $offers[ 0 ], $atts['store'] );

            return $this->FillTemplate( $offers[ 0 ], $tracking->ga_event, $tracking->tracking_id, $this->templates['affiliate_link'] );
        }
            
        return '';        
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
   


}
