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

    private $template = <<<HTML
    <div class="blz_aff_gen_button">
        <a data-blz-affiliation-vars="{{ ga_event }}" 
        class="btn custom_btn" 
        href="{{ url }}"
        >{{ content }}</a>
    </div>
    <style>.blz_aff_gen_button { text-align:"center" }</style>
    HTML;

    function __construct() {

        // Add the shortcode to print the links
        add_shortcode( 'affiliate_generic', [ $this, 'print'] );
    }


   /**
     * Stampa il bottone impostato da shortcode
     *     
     */
    public function printAffiliateLink( $atts, $content, $tag ) {
        
        /// prendo la request
        $this->request = new Request($atts);

        /// inizializzo i settingsData 
        $SettingsData = new SettingsData("genericButton",$this->request);

        return $this->FillTemplate( $SettingsData->getGAEvent(), $SettingsData->getTrackingID(), $this->template );

    }


    private function FillTemplate( $ga_event, $tracking, $template) {

        /// accorcia il link
        $link = ( new Shortener )->generateShortLink( $link ) ;

        $content = $this->request->getContent();

        return str_replace([ '{{ url }}', '{{ ga_event }}', '{{ content }}' ], [ $link, $ga_event, $content ], $template);
    }


}
