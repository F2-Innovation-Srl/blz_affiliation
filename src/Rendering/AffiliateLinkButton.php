<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Shortener;

use BLZ_AFFILIATION\Utils\FileGetContents;
use BLZ_AFFILIATION\AffiliateMarketing\Offer;

use BLZ_AFFILIATION\AffiliateMarketing\OffersRetriever;
use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Rendering\postData;
use BLZ_AFFILIATION\Rendering\settingsData;



class AffiliateLinkButton {

    private $post;
    private $category;
    private $is_paid;
    private $author;
    private $postData;

    public function __construct() {

        add_shortcode( 'affiliate_link',  [ $this, 'printAffiliateLink'] );

    }


    private function FillTemplate( Offer $offer, $ga_event, $tracking, $template, $request) {

        $link = str_replace( '{tracking-id}', $tracking, $offer->link);

        $content = (!empty($request->getContent())) ? $request->getContent() : $offer->price . " euro";

        return str_replace([ '{{ url }}', '{{ ga-event }}', '{{ content }}' ], [ $link, $ga_event, $content ], $template);
    }

    
    private function getTracking( Offer $offer, $store ) {

        $class = "mtz cta vg ".$this->category." editorial " . $author . " " .  $atts["marketplace"];
        
        
        $ga_event = str_replace(
            [ '{{ website }}', '{{ category }}', '{{ author }}', '{{ marketplace }}'],
            [ $this->domain, $this->category, $this->author->analytics, $offer->marketplace . $this->paid ],
            settingsData::getTemplate("ga_event")
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
        $this->postData = new PostData();
        /// prendo la request
        $request = new Request($atts);
        /// cerca le offerte nei tre marketplace
        /// effettua una chiamata a querydispatcher 
        /// per ogni marketplace        
        $offerRetriever = new OffersRetriever($request);

        /// riceve le offerte in ordine di marketplace
        $offers = $offerRetriever->getOffers();

        if( !empty( $offers ) ){

            $tracking = $this->getTracking( $offers[ 0 ], $atts['tracking_id'] );

            return $this->FillTemplate( $offers[ 0 ], $tracking->ga_event, $tracking->tracking_id, settingsData::getTemplate("affiliate_link"),$request );
        }
            
        return '';        
    }

    


}
