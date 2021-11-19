<?php

namespace BLZ_AFFILIATION\AffiliateMarketing;

class Request {

    private $keyword;
    private $code;
    private $min_price;

    function __construct( $args ) 
    {
        $this->keyword = isset( $args['keyword'] ) ? $args['keyword'] : '__default__';
        $this->code    = isset( $args['code'] ) ? $args['code'] : null;
        $this->min_price    = isset( $args['min_price'] ) ? $args['min_price'] : null;
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
    public function getCode()    { return ( $this->code ) ? $this->code : ''; } 
    public function getMinPrice()    { return ( $this->min_price ) ? $this->min_price : 20; } 
}
