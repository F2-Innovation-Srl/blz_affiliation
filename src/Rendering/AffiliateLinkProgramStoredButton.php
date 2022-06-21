<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Helper;
/**
 * 
 * Sostiruisce nella pagina il link selezionato al posto 
 * dello shortcode
 * 
 */
class AffiliateLinkProgramStoredButton {

    function __construct() {

        // Add the shortcode to print the links
        add_shortcode( 'affiliate_program_stored_link', [ $this, 'printAffiliateLink'] );
    }

    
    public function printAffiliateLink ( $atts, $content, $tag ) {

        Helper::isAffiliationPage();
        /// prende i dati della request e del content
        /// a partire dal link_id
        $link = get_post( $atts['id'] );

        $shortcode = $link->post_content;


        /// se viene inserito il content
        if( trim( $content ) != '' )
            $shortcode = preg_replace('/\[affiliate_program_link(.*?)\].*?\[\/affiliate_program_link\]/', "[affiliate_program_link $1]" . $content . '[/affiliate_program_link]', $shortcode);

        /// ritorna il rendering dello shortcode
        return do_shortcode( $shortcode );
    }

}
