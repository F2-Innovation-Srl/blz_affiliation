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
     * Prende i parametri dello shortcode e ritorna il markup del bottone 
     * 
     * @param [type] $atts
     * @param [type] $content
     * @param [type] $tag
     * @return void
     */
    public function printAffiliateLink( $atts, $content, $tag ) {
        
        $url = isset( $atts["url"] ) ? $atts["url"] : '';
        
        if( empty( $url ) ) return '';

        /// tutti i nomi delle classi che contengono dei modelli per il match di link
        /// per i diversi marketplace
        $patternClassNames = Helper::getMarketplacePatterns();

        /// array che contiene oggetti "Link" per il link rilevato (oppure insieme vuoto)
        $links = array_map( function( $patternClassName ) use ( $url ) {

            $patternClass = 'BLZ_AFFILIATION\\Rendering\\ParseLinkAndRender\\Patterns\\' . $patternClassName;

            /// gli oggetti pattern fanno in realtà pattern matching e prendono 
            /// in ingresso un content che deve essere arricchito/modificato        
            /// in questo caso creiamo un markup per il nostro link 
            /// e lo passiamo come content minimale
            $pattern = new $patternClass( '<a href="' . $url . '">' );

            /// prendiamo solo il primo dei dati ( se data non è vuoto )
            $link = empty( $pattern->data ) ? null : array_shift( $pattern->data );

            return $link;

        }, $patternClassNames ); 

        /// filtra solo i link che non risultano vuoti ( ce ne dovrebbe essere uno solo o nessuno )
        $links = array_filter( $links, function( $link ) { return !empty( $link ); } );

        /// se non ce n'è alcuno ritorna stringa vuota
        if( empty( $links ) ) return '';

        /// altrimenti salva il primo link ( anche l'unico ) nella property linkData
        $this->linkData = array_shift( $links );
            
        /// Arricchisce le atts passate allo shortcode
        /// impostando il marketplace corrente
        $atts[ "marketplace" ] = $this->linkData->marketplace;

        /// effettua la request
        $this->request = new Request( $atts );

        /// inizializza i settingsData per arricchire con gli attributi il link
        $settingsData = new SettingsData( "parseLinkAndRender", $this->request );

        /// ritorna il markup definitivo
        return $this->FillTemplate( $settingsData->getGAEvent(), $settingsData->getTrackingID(), $settingsData->getTemplate() );
    }


    private function FillTemplate( $ga_event, $tracking, $template) {
      
        Helper::setAffiliationPage();
    
        $link = Helper::cleanParams( $this->linkData->url );

        $link = empty( $link ) ? $this->linkData->url : $link;

        /// questa patch non dovrebbe esistere a questo livello. Bisognerà fare un refactoring.
        /// se manca il tracking id ed è un link amazon aggiunge il tag
        if( strpos($link, 'tracking') === false ) {

            /// deve recuperare i settings
            $settings = get_option( "blz-affiliation-settings" );

            /// recupera il campaign id
            $ebay_campaing_id = ( isset( $settings['ebay_campain_id'] )) ? $settings['ebay_campain_id'] : "5338741871";

            $tracking_suffix_map = [

                'amazon' =>  '?tag={tracking-id}',
                'ebay'   =>  '?mkevt=1&toolid=10001&mkcid=1&mkrid=724-53478-19255-0&siteid=101&campid='.$ebay_campaing_id.'&customid={tracking-id}'
            ];

            $link = $link . $tracking_suffix_map[ $this->linkData->marketplace ];
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