<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Shortener;

use BLZ_AFFILIATION\Rendering\PostData;



/**
 * 
 */
class AffiliateLinkProgramsButton {

    private $postData;
    private $request;

    private $templates = [
        
        'affiliate_link' => <<<HTML

            <a href="{{ url }}" data-vars-affiliate="{{ ga_event }}" 
               class="affiliation-intext" target="_blank" rel="sponsored"
            >{{ content }}</a>
        HTML,

        'ga_event' => <<<HTML
            mtz cta {{ website }} {{ category }} editorial {{ author }} {{ marketplace }}
        HTML
    ];


    public function __construct() {

        /**
         * 
         * this.shortcode
         * [affiliate_program_link
         *      link="${link}"
         *      author_tracking_id="${author_tracking_id}
         *      subject="${subject}" 
         *      program="${program}"
         * ]
         */ 
        add_shortcode( 'affiliate_program_link',  [ $this, 'printAffiliateLink'] );
    }


 
    /**
     * Stampa il bottone impostato da shortcode
     *     
     */
    public function printAffiliateLink( $atts, $content, $tag ) {

        /// prende tutti i dati del post
        $this->postData = new PostData();

        /// prendo la request
        $this->request = $atts;

        $tracking = $this->getTracking();

        
        return $this->FillTemplate( $tracking->ga_event, $tracking->tracking_id, $this->templates['affiliate_link'] );
    }


    private function FillTemplate( $ga_event, $template ) {

        $link    = ( new Shortener )->generateShortLink( $this->request['link'] ) ;
        $content = $this->request['content'];
        
        return str_replace([ '{{ url }}', '{{ ga-event }}', '{{ content }}' ], [ $link, $ga_event, $content ], $template );
    }

    
    /**
     * Genera un oggetto che ha sia il ga_event, sia il tracking id
     * bisogna capire se serve
     *
     * @return object { ga_event, tracking_id }
     */
    private function getTracking() {
    
        $ga_event = str_replace(
            [ '{{ website }}', '{{ category }}', '{{ author }}', '{{ marketplace }}'],
            [ $this->domain, $this->category, $this->author->analytics, $offer->marketplace . $this->paid ],
            $this->templates["ga_event"]
        );


        /// [ REVIEW ]  vedere se il tracking id è corretto 
        ///             credo che sia da rivedere tutto il tracciamento
        ///             dei link "ex prettylink"

        $tracking = $this->request['author_tracking_id'] . ' ' . $this->request['subject'] . ' ' . $this->request['prova'];

        return (object) [
            'ga_event'    => $ga_event,
            'tracking_id' => $tracking
        ];
    }


   


}
