<?php

namespace BLZ_AFFILIATION\AffiliateMarketing;

class Request {

    private $keyword;
    private $code;

    function __construct( $args ) 
    {
        $this->keyword = isset( $args['keyword'] ) ? $args['keyword'] : '__default__';
        $this->code    = isset( $args['code'] ) ? $args['code'] : null;
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
}
