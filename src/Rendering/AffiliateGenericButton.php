<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Rendering\Settings\SettingsData;
use BLZ_AFFILIATION\Utils\Helper;
use BLZ_AFFILIATION\Utils\Shortener;


/**
 * Stampa un link di tipo button 
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
        $patterns = array_map( function( $patternClass ) use ($atts) {

            $patternClass = 'BLZ_AFFILIATION\\Rendering\\ParseLinkAndRender\\Patterns\\' . $patternClass;
            return new $patternClass("<a href=\"".$atts["url"]."\">");
        },  Helper::getMarketplacePatterns() );

       
        
        /// cerca il link tra i pattern
        foreach( $patterns as $pattern ) {
            foreach( $pattern->data as $linkData ) {

                if ( $linkData->url == $atts['url'] ) $this->linkData = $linkData;
            }                
        }
            
        $atts[ "marketplace" ] = $this->linkData->marketplace;

        /// prende la request
        $this->request = new Request( $atts );

        /// inizializza i settingsData 
        $settingsData = new SettingsData("parseLinkAndRender",$this->request);

        return $this->FillTemplate( $settingsData->getGAEvent(), $settingsData->getTrackingID(), $settingsData->getTemplate() );
    }


    private function FillTemplate( $ga_event, $tracking, $template) {
      
        Helper::setAffiliationPage();
    
        $link = Helper::cleanParams( $this->linkData->url );

        $link = empty( $link ) ? $this->linkData->url : $link;

        /// questa patch non dovrebbe esistere a questo livello. Bisognerà fare un refactoring.
        /// se manca il tracking id ed è un link amazon aggiunge il tag
        if( $this->linkData->marketplace == 'amazon' && strpos($link, 'tracking') === false ) {

            $link = $link . '?tag={tracking-id}';
        }
                
        $link = str_replace(['{tracking_id}','{tracking-id}','%7Btracking-id%7D'], $tracking, $link);
        
        if (strpos($this->linkData->url, "ebay") === false) {

            $link = ( new Shortener )->generateShortLink( $link );
        }
             
        $content = $this->request->getContent();

        $rand_suffix = implode( '', array_map( function( ) { return chr( rand(65,85) ); }, range(0,10) ));

        $class_name = 'blz_btn_' . $rand_suffix;

        return str_replace(
            [ '{{ url }}', '{{ ga_event }}', '{{ content }}', '{{ obfuscated_class }}' ],
            [ $link, $ga_event, $content, $class_name ], 
            $template
        );
    }


}
