<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\AffiliateMarketing\Offer;

use BLZ_AFFILIATION\AffiliateMarketing\OffersRetriever;
use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Rendering\SettingsData;
use BLZ_AFFILIATION\Utils\Shortener;


class AffiliateLinkButton {

    private $post;
    private $category;
    private $is_paid;
    private $author;
    private $request;

    public function __construct() {

        add_shortcode( 'affiliate_link',  [ $this, 'printAffiliateLink'] );
        
    }


    private function FillTemplate( Offer $offer, $ga_event, $tracking, $template) {

        $link = str_replace( '{tracking-id}', $tracking, $offer->link);
        /// poi accorcia il link
        $link = ( new Shortener )->generateShortLink( $link ) ;

        $content = (!empty($this->request->getContent())) ? $this->request->getContent() : $offer->price . " euro";
        return str_replace([ '{{ url }}', '{{ ga_event }}', '{{ content }}' ], [ $link, $ga_event, $content ], $template);
    }


    /**
     * Stampa il bottone impostato da shortcode
     *     
     */
    public function printAffiliateLink( $atts, $content, $tag ) {

        /// prende la request
        $this->request = new Request($atts);

        /// cerca le offerte nei marketplace
        /// effettua una chiamata a querydispatcher 
        /// per ogni marketplace        
        $offerRetriever = new OffersRetriever($this->request);

        /// riceve le offerte in ordine di marketplace
        $offers = $offerRetriever->getOffers();

          
        if( !empty( $offers ) ){
            
            /// inizializzo i settingsData 
            $SettingsData = new SettingsData("linkButton",$this->request);
           
            return $this->FillTemplate( $offers[ 0 ], $SettingsData->getGAEvent(), $SettingsData->getTrackingID(), $SettingsData->getTemplate() );
        }
            
        return '';        
    }

    


}
