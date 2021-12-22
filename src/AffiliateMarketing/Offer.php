<?php

namespace BLZ_AFFILIATION\AffiliateMarketing;

class Offer {

    public $price;
    public $link;
    public $marketplace;

    function __construct( $args ) 
    {
        $this->price       = isset( $args['price'] )        ? $args['price'] : '';
        $this->link        = isset( $args['link'] )         ? $args['link'] : '';
        $this->marketplace = isset( $args['marketplace'] )  ? $args['marketplace'] : '';
    }
}
