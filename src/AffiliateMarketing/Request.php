<?php

namespace BLZ_AFFILIATION\AffiliateMarketing;

class Request {

    private $marketplace;
    private $keyword;
    private $code;
    private $min_price;

    function __construct( $args ) 
    {
        $this->marketplace = isset( $args['marketplace'] ) ? $args['marketplace'] : 'amazon';
        $this->keyword = isset( $args['keyword'] ) ? $args['keyword'] : '__default__';
        $this->code    = isset( $args['code'] ) ? $args['code'] : null;
        $this->min_price    = isset( $args['min_price'] ) ? $args['min_price'] : '20';
    }

    /**
     * return true if 'code' exists
     *
     * @return boolean
     */
    public function hasCode() {

        return $this->code != null;
    }


    public function getKeyword() { return $this->keyword; } 
    public function getCode()    { return  $this->code; } 
    public function getMinPrice()    { return $this->min_price; } 
    private function getMarketplace(String $marketplace) {

        $marketplace = [
            "ebay" => "Ebay" ,
            "ebay_used" => "EbayUsed", 
            "trovaprezzi" => "Trovaprezzi",  
            "amazon" => "Amazon", 
            "custom" => "custom"
        ];
        return $MarketPlaceMap[$marketplace];
    }

}
