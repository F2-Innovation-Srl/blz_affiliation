<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Shortener;

use BLZ_AFFILIATION\Utils\FileGetContents;
use BLZ_AFFILIATION\AffiliateMarketing\Offer;

use BLZ_AFFILIATION\AffiliateMarketing\OffersRetriever;
use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Rendering\PostData;
use BLZ_AFFILIATION\Rendering\SettingsData;



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

            /// inizializzo i settingsData 
            $SettingsData = new SettingsData($postData,"affiliate_link",$request->getMarketplaceKey());
            return $this->FillTemplate( $offers[ 0 ], $SettingsData->getGAEvent(), $SettingsData->getTrackingID(), $SettingsData->getTemplate(),$request );
        }
            
        return '';        
    }

    


}
