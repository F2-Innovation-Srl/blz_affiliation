<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Shortener;

use BLZ_AFFILIATION\Utils\FileGetContents;
use BLZ_AFFILIATION\AffiliateMarketing\Offer;

use BLZ_AFFILIATION\AffiliateMarketing\OffersRetriever;
use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Rendering\PostData;
use BLZ_AFFILIATION\Rendering\SettingsData;



class AffiliateCustomLinkButton {

    private $post;
    private $category;
    private $is_paid;
    private $author;
    private $postData;


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

        /// prende tutti i dati del post
        $this->postData = new PostData();
        /// prendo la request
        $request = new Request($atts);
        $tracking = $this->getTracking( null, $atts['ga_event'] );

        return $this->FillTemplate( $tracking->ga_event, $tracking->tracking_id, SettingsData::getTemplate("affiliate_link"),$request );

    }


    private function FillTemplate( $ga_event, $tracking, $template, $request) {

        $link = str_replace( '{tracking-id}', $tracking, $request->getLink());

        $content = $request->getContent();

        return str_replace([ '{{ url }}', '{{ ga-event }}', '{{ content }}' ], [ $link, $ga_event, $content ], $template);
    }

    // #REVIEW
    private function getTracking( $ga_event, $tracking ) {

        return (object) [
            'ga_event'    => $ga_event,
            'tracking_id' => $tracking
        ];
    }
   


}
