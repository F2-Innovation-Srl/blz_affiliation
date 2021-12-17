<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Shortener;
use BLZ_AFFILIATION\AffiliateMarketing\Request;
/**
 * Sostiruisce nella pagina il link selezionato al posto 
 * dello shortcode
 */
class AffiliateLinkProgramsButton {


    protected $request;
    protected $content;


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

        /// prendo la request
        $this->request = new Request($atts,$content);
        
        /// inizializzo i settingsData 
        $SettingsData = new SettingsData("linkPrograms",$this->request);
        
        return $this->FillTemplate($SettingsData->getGAEvent(), $SettingsData->getTrackingID(), $SettingsData->getTemplate() );

    }


    private function FillTemplate( $ga_event, $tracking_id, $template ) {

        /// se possibile inserisce il tracking id
        $link = str_replace( '{tracking-id}', $tracking_id, $this->request->getLink() );
        /// poi accorcia il link
        $link = ( new Shortener )->generateShortLink( $link ) ;
        
        return str_replace([ '{{ url }}', '{{ ga_event }}', '{{ content }}' ], [ $link, $ga_event, $this->request->getContent() ], $template);
             
    }

  
}