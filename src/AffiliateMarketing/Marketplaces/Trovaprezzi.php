<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;

class Trovaprezzi implements Marketplace {

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

        $tracking = $atts["store"];

        return $tracking;
    }

    public function getQueryURL() {

        $api      = "https://querydispatcher.justearn.it/api/v1/getoffer/";
        $mktplace = "trovaprezziVG";
        $params    = "/items/1/category/elettronica?min_price=20";

        $url_template = $api. '{{ query }}' . '/marketplace/' . $mktplace . $params;
      
        $url = ( $this->keyword != null ) ? str_replace( '{{ query }}', urlencode( $this->keyword ), $url_template ) : '';     

        $url = ( $this->code != null ) ? str_replace( '{{ query }}', urlencode( $this->code ), $url_template ) . '&code=true' : $url;

        return $url;
    }
}

