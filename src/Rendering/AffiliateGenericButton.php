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

    /**
     * Imposta i pattern da verificare
     *
     * @return array
     */
    private function setPatterns() {

        return [
            'Amazon',
            'Ebay',
            'AmazonShorted', 
            'AmazonPrimeVideo',
            'EbayShorted', 
            'PrettyLink', 
        ];
    }

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

        // recupera i pattern per ogni 
        /// tipologia di link da sostituire
        $patterns = array_map( function( $patternClass ) {
            $patternClass = 'BLZ_AFFILIATION\\Rendering\\ParseLinkAndRender\\Patterns\\' . $patternClass;
            return new $patternClass("<a href=\"".$this->request->getLink()."\">");

        },  $this->setPatterns() );
        /// a questo punto dovremmo avere tutti
        /// gli elementi per costruire i link
   
        /// per ogni pattern
        foreach( $patterns as $pattern ) 
            // cerchiamo il link
            foreach( $pattern->data as $linkData ) 
                //echo "<pre>";
                if ($linkData->url) 
                    $link = ( new Shortener )->generateShortLink( str_replace( '{tracking_id}', $tracking, $linkData->url));

       
        $content = $this->request->getContent();

        return str_replace([ '{{ url }}', '{{ ga_event }}', '{{ content }}' ], [ $link, $ga_event, $content ], $template);
    }


}
