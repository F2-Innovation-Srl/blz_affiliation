<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;

use BLZ_AFFILIATION\Utils\Helper;
class Ebay extends Marketplace {

    /**
     * Costruisce una query per il querydispatcher
     * specifica per Ebay
     * 
     * @return void
     */
    public function getQueryURL() {

        /// per default prende una keyword
        $query = $this->request->getKeyword();
    
        /// se la request contiene un codice
        if ( $this->request->hasCode() ){
    
            /// restituisce il codice e la tipologia
            $code = $this->whatCode( $this->request->getCode() );

            /// cerca il codice 
            $query =   $code;
        }

        /// sostituisce i valori nella query
        $apiQuery  = str_replace(
            [ 
                '{{ query }}', 
                '{{ marketplace }}'
            ],
            [ 
                urldecode( $query ),
                Helper::getApiSlug("ebay")
            ], 
            $this->apiQuery 
        );
        
        $apiParams = str_replace( '{{ min_price }}',  $this->request->getMinPrice() , $this->apiParams );

        /// ritorna la query
        return $this->apiBase . $apiQuery . $apiParams;
    }

    /**
     * ritorna tipo e codice rilevati
     *
     * @param string $code
     * @return object
     */
    private function whatCode( string $code ) {

        /// codice prodotto
        $ebay_id = preg_filter( '/.*www.ebay.*\/(\d+)\?.*/', '$1', $code );

        if (!$ebay_id)  $ebay_id = $code;
        
        /// ritorna tipo e codice rilevati
        return $code; 
    }
      
}



