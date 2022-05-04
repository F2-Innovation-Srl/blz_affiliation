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
    private $linkData;

    function __construct() {
        // Add the shortcode to print the links
        add_shortcode( 'affiliate_generic', [ $this, 'printAffiliateLink'] );
    }


   /**
     * Stampa il bottone impostato da shortcode
     *     
     */
    public function printAffiliateLink( $atts, $content, $tag ) {
        
       

        // recupera i pattern per ogni 
        /// tipologia di link da sostituire
        $patterns = array_map( function( $patternClass ) use ($atts){
            $patternClass = 'BLZ_AFFILIATION\\Rendering\\ParseLinkAndRender\\Patterns\\' . $patternClass;
            return new $patternClass("<a href=\"".$atts["url"]."\">");
        },  Helper::getMarketplacePatterns() );

        /// cerco tra i patterns il mio link 
        foreach( $patterns as $pattern ) 
            foreach( $pattern->data as $linkData ) 
                if ($linkData->url) $this->linkData = $linkData;


        $atts["marketplace"] = $this->linkData->marketplace;


        /// prendo la request
        $this->request = new Request($atts);
        /// inizializzo i settingsData 
        $SettingsData = new SettingsData("parseLinkAndRender",$this->request);
        return $this->FillTemplate( $SettingsData->getGAEvent(), $SettingsData->getTrackingID(), $SettingsData->getTemplate() );

    }


    private function FillTemplate( $ga_event, $tracking, $template) {
      
        $link = ( new Shortener )->generateShortLink( str_replace( '{tracking_id}', $tracking, $this->linkData->url));

        $content = $this->request->getContent();

        return str_replace([ '{{ url }}', '{{ ga_event }}', '{{ content }}' ], [ $link, $ga_event, $content ], $template);
    }


}
