<?php

namespace BLZ_AFFILIATION\Rendering\ParseLinkAndRender;

use BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Link;
use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Rendering\Settings\SettingsData;
use BLZ_AFFILIATION\Utils\Shortener; 
use BLZ_AFFILIATION\Utils\Helper;

class ParseLinkAndRender {

    private $content;


    public function __construct() {

        // Add the custom columns to the posts post type:
        add_filter( 'the_content', [ $this, 'parseAndRender'] );
    }

    /**
     * Sostituisce i link nel testo 
     *
     * @param string $content
     * @return string
     */
    public function parseAndRender( string $content ) {

        /// se non è una single non fa nulla
        if ( !is_singular() ) return $content;

        $this->content = $content;
        
        /// recupera i pattern per ogni 
        /// tipologia di link da sostituire
        $this->patterns = array_map( function( $patternClass ) {
            $patternClass = 'BLZ_AFFILIATION\\Rendering\\ParseLinkAndRender\\Patterns\\' . $patternClass;
            $content = $this->content;
            return new $patternClass($content);

        },  Helper::getMarketplacePatterns()  );
        /// a questo punto dovremmo avere tutti
        /// gli elementi per costruire i link
   
        
        /// per ogni pattern
        foreach( $this->patterns as $pattern ) {
           
            // sostituiamo tutti i link che abbiamo trovato
            foreach( $pattern->data as $linkData ) {

                /// inizializziamo i settingsData 
                $SettingsData = new SettingsData("parseLinkAndRender",(new Request(["marketplace" => $linkData->marketplace])));
                $new_link = $this->fillTemplate( $pattern->name, $linkData->url, $SettingsData->getGAEvent(), $SettingsData->getTrackingID(), $SettingsData->getTemplate() );
                
                /// rimpiazziamo i vecchi link con i nuovi
                $this->content = str_replace( $linkData->old_link, $new_link, $this->content );
            }
        }
        
        return $this->content;
    }

    /**
     * Crea il link a partire dai dati della pagina
     * e da quelli di un singolo link nel testo
     *
     * @param Link $linkData
     * @return string
     */
    private function fillTemplate( $marketplace, $link, $ga_event, $tracking, $template) {
            
        Helper::isAffiliationPage();

        if (strpos($marketplace, "ebay") === false) {
            $link = str_replace(['{tracking_id}','{tracking-id}','%7Btracking-id%7D'], $tracking, $link);
            $link = ( new Shortener )->generateShortLink( $link );

        } else{
            $link = Helper::cleanEbayParams($link);
            $link = str_replace(['{tracking_id}','{tracking-id}','%7Btracking-id%7D'], $tracking, $link);
        } 
        
        return str_replace([ '{{ url }}', '{{ ga_event }}' ], [ $link, $ga_event ], $template);
    }

}
