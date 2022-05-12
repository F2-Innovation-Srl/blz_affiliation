<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Shortener;

use BLZ_AFFILIATION\Utils\FileGetContents;
use BLZ_AFFILIATION\AffiliateMarketing\Offer;

use BLZ_AFFILIATION\AffiliateMarketing\OffersRetriever;
use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Rendering\Settings\SettingsData;
use BLZ_AFFILIATION\Utils\Helper;

class AffiliateCustomLinkButton {

    private $post;
    private $category;
    private $is_paid;
    private $author;
    private $request;

    public function __construct() {

        /// [ REVIEW ] documentiamo che differenza c'è tra i due

        // Add the shortcode to print the links
        add_shortcode( 'affiliate_track', [ $this, 'printAffiliateTracking'] );

    }


    /**
     * Stampa il bottone impostato da shortcode
     *     
     */
    public function printAffiliateTracking( $atts, $content, $tag ) {
        
        /// prendo la request
        $this->request = new Request($atts);

        /// inizializzo i settingsData 
        $SettingsData = new SettingsData("linkButton",$this->request);

        return $this->FillTemplate( $SettingsData->getGAEvent(), $SettingsData->getTrackingID(), $SettingsData->getTemplate() );

    }


    private function FillTemplate( $ga_event, $tracking, $template) {

        Helper::isAffiliationPage();
        $link = str_replace(['{tracking-id}','%7Btracking-id%7D'], $tracking, $this->request->getLink());
        /// poi accorcia il link
        $link = ( new Shortener )->generateShortLink( $link ) ;

        $content = $this->request->getContent();

        return str_replace([ '{{ url }}', '{{ ga_event }}', '{{ content }}' ], [ $link, $ga_event, $content ], $template);
    }
   


}
