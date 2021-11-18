<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;
class Ebay implements Marketplace {

    protected $name = "ebay";
    
   
    public function getQueryURL() {

        /// per default prende una keyword
        $query = $this->request->getKeyword();
        $code_suffix = '';
        
        /// se la request contiene un codice
        if ( $this->request->hasCode() ){
    
            /// restituisce il codice e la tipologia
            $code = $this->whatCode( $this->request->getCode() );

            /// cerca il codice 
            $query =  $code->value;
            
            $code_suffix = ( $code->type == 'epid' ) ? '&code=true' : '';
        }

        /// sostituisce i valori nella query
        $apiQuery = str_replace(['{{ query }}', '{{ marketplace }}'], [ urldecode( $query ), $this->name ], $this->apiQuery );
        
        /// ritorna la query
        return $this->apiBase . $apiQuery . $this->apiParams . $code_suffix;
    }

    /**
     * ritorna tipo e codice rilevati
     *
     * @param string $code
     * @return object
     */
    private function whatCode( string $code ){

        /// cerca il codice EPID
        $epid = preg_filter( '/EPID(.+)/', '$1', $code );
            
        /// codice prodotto
        $ebay_id = preg_filter( '/.*www.ebay.*\/(\d+)\?.*/', '$1', $code );
        
        /// ritorna tipo e codice rilevati
        return (object) ( $epid ) ? [
            'type'  => 'epid',
            'value' => $epid
        ] : [
            'type'  => 'ebay_id',
            'value' => $ebay_id
        ]; 
    }
      
}



