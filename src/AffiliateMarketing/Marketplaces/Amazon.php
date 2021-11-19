<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;
class Amazon implements Marketplace {

    protected $name = 'amazon';

    public function getQueryURL() {

        /// per default prende una keyword
        $query = $this->request->getKeyword();
        $code_suffix = '';
        
        /// se la request contiene un codice
        if ( $this->request->hasCode() ){
    
            /// restituisce il codice e la tipologia
            $code = $this->whatCode( $this->request->getCode() );

            $query       =  $code->value;            
            $code_suffix = '&code=true';
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

        $amazon_id = preg_filter( '/.*www.amazon.*\/(.+)\/[ref|\?].*/', '$1', $code);
        if (!$amazon_id)  $amazon_id = $code;
        /// codice ASIN
        return (object) [
            'type'  => 'asin',
            'value' =>  $amazon_id
        ];                
    }

}





