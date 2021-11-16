<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;

class Amazon implements Marketplace {


    public function getOffers() {

        $offers_json = FileGetContents::getContent( $this->getQueryURL() );

        return array_map( function( $offer ) {

            return [
                'url'   => $offer[ 'link' ],
                'price' => $offer[ 'price' ]
            ];

        }, json_decode( $offers_json, true ) );

    }

    public function tracking() {

        $tracking = (empty($atts["store"])) ? get_field('amazon_traking_id_editorial', 'user_'.$author_id) : $atts["store"];   
        
        if (empty($tracking)) $tracking = "vg-classifiche-editorial-21";        

        return $tracking;
    }


    public function getQueryURL() {

        $searchWithCode = '&code=true';

        if( $this->code !== null) {

            // CASO TROVA CODICE DA LINK
            if ( strrpos( $this->code, "www.amazon" ) !== false) {

                $code = explode( "/", explode( "?" , $this->code )[0] );
                
                if ( strrpos( $code[count($code)-1 ], "ref") !== false ) 
                    $this->code = $code[ count($code) - 2 ];
                else
                    $this->code = $code[count($code)-1];
            }

        }


        $api      = "https://querydispatcher.justearn.it/api/v1/getoffer/";
        $mktplace = "amazon";
        $params   = "/items/1/category/elettronica?min_price=20";

        $url_template = $api. '{{ query }}' . '/marketplace/' . $mktplace . $params;
      
        $url = ( $this->keyword != null ) ? str_replace( '{{ query }}', urlencode( $this->keyword ), $url_template ) : '';     

        $url = ( $this->code != null ) ? str_replace( '{{ query }}', urlencode( $this->code ), $url_template ) . $searchWithCode : $url;

        return $url;
    }
}


