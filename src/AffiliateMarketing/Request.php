<?php

namespace BLZ_AFFILIATION\AffiliateMarketing;

class Request {

    private $marketplace;
    private $keyword;
    private $code;
    private $min_price;
    // FOR CUSTOM URL
    private $link;
    private $ga_event;
    private $subject;
    private $program;
    private $position;
    private $type;

    function __construct( $args, $content = '' ) 
    {
        $this->marketplace  = isset( $args['marketplace'] ) ? $args['marketplace'] : 'amazon';
        $this->keyword      = isset( $args['keyword'] ) ? $args['keyword'] : '__default__';
        $this->code         = isset( $args['code'] ) ? $args['code'] : (isset( $args['asins'] ) ? $args['asins'] : null); //CONTROLLO ASIN PER RETROCOMPATIBILITA'
        $this->min_price    = isset( $args['min_price'] ) ? $args['min_price'] : '20';
        $this->content      = !empty( $content ) ? $content : (isset( $args['text'] ) ? $args['text'] : '');
        $this->tracking_id  = isset( $args['tracking_id'] ) ? $args['tracking_id'] : (isset( $args['store'] ) ? $args['store'] : null); //CONTROLLO store PER RETROCOMPATIBILITA'
        $this->link         = isset( $args['url'] ) ? $args['url'] : (isset( $args['link'] ) ? $args['link'] : '');
        $this->ga_event     = isset( $args['ga_event'] ) ? $args['ga_event'] : (isset( $args['data-affiliate'] ) ? $args['data-affiliate'] : null); //CONTROLLO data-affiliate PER RETROCOMPATIBILITA'
        $this->subject      = isset( $args['subject'] ) ? $args['subject'] : '';
        $this->program      = isset( $args['program'] ) ? $args['program'] : '';
        $this->position     = isset( $args['position'] ) ? $args['position'] : '';
        $this->type         = isset( $args['type'] ) ? $args['type'] : 'text';
       

    }

    /**
     * return true if 'code' exists
     *
     * @return boolean
     */
    public function hasCode() {

        return $this->code != null;
    }

    public function getKeyword()         { return $this->keyword; } 
    public function getCode()            { return $this->code; } 
    public function getMinPrice()        { return $this->min_price; } 
    public function getContent()         { return urldecode($this->content); } 
    public function getTrackingId()      { return $this->tracking_id; } 
    public function getLink()            { return $this->link; } 
    public function getGaEvent()         { return $this->ga_event; } 
    public function getSubject()         { return $this->subject; } 
    public function getProgram()         { return $this->program; } 
    public function getPosition()        { return $this->position; } 
    public function getMarketplaceKey()  { return $this->marketplace;}
    public function getType()            { return $this->type; } 
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
