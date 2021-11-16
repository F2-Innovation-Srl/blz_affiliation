<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;


abstract class Marketplace {

    protected $keyword;
    protected $code;

    public function __construct( array $args ) {

        $this->keyword = isset( $args['keyword'] ) ? $args['keyword'] : null;
        $this->code    = isset( $args['asins'] )   ? $args['asins']   : null;

    }

    abstract public function getOffers();

}
