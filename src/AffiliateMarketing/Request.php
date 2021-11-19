<?php

namespace BLZ_AFFILIATION\AffiliateMarketing;

class Request {

    private $marketplace;
    private $keyword;
    private $code;
    private $min_price;

    function __construct( $args ) 
    {
        $this->marketplace  = isset( $args['marketplace'] ) ? $args['marketplace'] : 'amazon';
        $this->keyword      = isset( $args['keyword'] ) ? $args['keyword'] : '__default__';
        $this->code         = isset( $args['code'] ) ? $args['code'] : (isset( $args['asins'] ) ? $args['asins'] : null); //CONTROLLO ASIN PER RETROCOMPATIBILITA'
        $this->min_price    = isset( $args['min_price'] ) ? $args['min_price'] : '20';
        $this->content      = isset( $args['text'] ) ? $args['text'] : '';
        $this->tracking_id  = isset( $args['tracking_id'] ) ? $args['tracking_id'] : (isset( $args['store'] ) ? $args['store'] : null); //CONTROLLO store PER RETROCOMPATIBILITA'
    }

    /**
     * return true if 'code' exists
     *
     * @return boolean
     */
    public function hasCode() {

        return $this->code != null;
    }

    public function getKeyword()     { return $this->keyword; } 
    public function getCode()        { return  $this->code; } 
    public function getMinPrice()    { return $this->min_price; } 
    public function getContent()      { return $this->content; } 
    public function getTrackingId()  { return $this->tracking_id; } 
    public function getMarketplace() {

        $MarketPlaceMap = [
            "ebay" => "Ebay" ,
            "ebay_used" => "EbayUsed", 
            "trovaprezzi" => "Trovaprezzi",  
            "amazon" => "Amazon", 
            "custom" => "custom"
        ];
        return isset($MarketPlaceMap[$this->marketplace]) ? $MarketPlaceMap[$this->marketplace] : $this->marketplace;
    }

}
