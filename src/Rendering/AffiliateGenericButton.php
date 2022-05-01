<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Utils\Helper;
use BLZ_AFFILIATION\Utils\Shortener;
/**
 * 
 * Ritorna i dati della tabella di affiliazione nella pagina
 * 
 */
class AffiliateGenericButton {

    protected $post_id;

    function __construct() {
        // Add the shortcode to print the links
        add_shortcode( 'affiliate_generic', [ $this, 'printAffiliateLink'] );
    }


   /**
     * Stampa il bottone impostato da shortcode
     *     
     */
    public function printAffiliateLink( $atts, $content, $tag ) {
        
        /// prendo la request
        $this->request = new Request($atts);

        /// inizializzo i settingsData 
        $SettingsData = new SettingsData("parseLinkAndRender",$this->request);

        return $this->FillTemplate( $SettingsData->getGAEvent(), $SettingsData->getTrackingID(), $SettingsData->getTemplate() );

    }


    private function FillTemplate( $ga_event, $tracking, $template) {
        /// accorcia il link
        $link = ( new Shortener )->generateShortLink( $this->request->getLink() ) ;

        $content = $this->request->getContent();

        return str_replace([ '{{ url }}', '{{ ga_event }}', '{{ content }}' ], [ $link, $ga_event, $content ], $template);
    }


}
