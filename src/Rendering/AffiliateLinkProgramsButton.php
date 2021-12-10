<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Shortener;

/**
 * Sostiruisce nella pagina il link selezionato al posto 
 * dello shortcode
 */
class AffiliateLinkProgramsButton {

    protected $domain = 'vg';
    protected $request;
    protected $content;

    protected $templates = [
        
        'affiliate_link' => <<<HTML

            <a href="{{ url }}" data-vars-affiliate="{{ ga_event }}" 
               class="affiliation-intext" target="_blank" rel="sponsored"
            >{{ content }}</a>
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
        //$this->postData = new PostData();
        /// prende tutti i dati del post
        $postData = new PostData();
        /// prendo la request
        $this->request = $atts;
        $this->content = $content;


        /// inizializzo i settingsData 
        $SettingsData = new SettingsData($postData,"linkPrograms",$request);
        
        return $this->FillTemplate( $offers[ 0 ], $SettingsData->getGAEvent(), $SettingsData->getTrackingID(), $SettingsData->getTemplate(),$request );
    

        $tracking = $this->getTracking();
        
        return $this->FillTemplate( $tracking->ga_event, $tracking->tracking_id, $this->templates['affiliate_link'] );
    }


    private function FillTemplate( $ga_event, $tracking_id, $template ) {

        /// se possibile inserisce il tracking id
        $link = str_replace( '{tracking-id}', $tracking_id, $this->request['link'] );

        /// poi accorcia il link
        $link = ( new Shortener )->generateShortLink( $link ) ;
        
        return trim( str_replace(
            [ '{{ url }}', '{{ ga_event }}', '{{ content }}' ],
            [ $link, $ga_event, $this->content ], 
            $template 
        ) );        
    }

    
    /**
     * Genera un oggetto che ha sia il ga_event, sia il tracking id
     * bisogna capire se serve
     * 
     * [ REVIEW ] riscrivere tutta la parte dei tracking ID 
     *
     * @return object { ga_event, tracking_id }
     */
    private function getTracking() {

        $amp = is_amp_endpoint() ? 'amp' : '';
    
        /// se è stato impostato un ga_event nello shortcode        
        $ga_event = isset( $this->request['ga_event'] ) ? 
        
            /// prendi quello
            $this->request['ga_event'] : 

            /// altrimenti usa il template
            trim(str_replace(
                [ '{{ domain }}', '{{ subject }}', '{{ program }}', '{{ amp }}' ],
                [ $this->domain, $this->request['subject'], $this->request['program'], $amp ],
                $this->templates["ga_event"]
            ));


        $tracking = $this->request['tracking_id'];

        return (object) [
            'ga_event'    => $ga_event,
            'tracking_id' => $tracking
        ];
    }
}