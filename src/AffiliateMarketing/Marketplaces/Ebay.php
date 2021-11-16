<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;

class Ebay implements Marketplace {


    public function getOffers() {

        // EBAY //
        // look for used E-BAY offer  using version's slug and replacing - with +
        $offers_json = FileGetContents::getContent( $this->getQueryURL() );

        return array_map( function( $offer ) {

            return [
                'url'   => $offer[ 'link' ],
                'price' => $offer[ 'price' ]
            ];

        }, json_decode( $offers_json, true ) );        
    }

    public function tracking() {

        $tracking = $atts["store"];
    
        $tracking = (empty($atts["store"])) ? get_field('ebay_traking_id_editorial', 'user_'.$author_id) : $atts["store"];   
        if (empty($tracking)) $tracking = "vgClassificheEditorial21";
    }

    public function getQueryURL() {

        $searchWithCode = '';

        // CASO TROVA CODICE DA LINK
        if ( strrpos( $this->code, "www.ebay" ) !== false) {

            $code = explode( "/" , $this->code );
            $code = explode( "?" , $code[ count($code)-1 ] )[0];
            $this->code = $code;
        
        } else {
        
            // CASO EPID OPPURE EAN INSERITO A MANO
            if ( strrpos( $this->code, "EPID" ) !== false ) {
            
                $searchWithCode ="&code=true";
                
                $this->code = str_replace( "EPID", "" , $this->code );

            } 
        }

        $api      = "https://querydispatcher.justearn.it/api/v1/getoffer/";
        $mktplace = "ebay";
        $params    = "/items/1/category/elettronica?min_price=20";

        $url_template = $api. '{{ query }}' . '/marketplace/' . $mktplace . $params;
      
        $url = ( $this->keyword != null ) ? str_replace( '{{ query }}', urlencode( $this->keyword ), $url_template ) : '';     

        $url = ( $this->code != null ) ? str_replace( '{{ query }}', urlencode( $this->code ), $url_template ) . $searchWithCode : $url;

        return $url;
    }
}



