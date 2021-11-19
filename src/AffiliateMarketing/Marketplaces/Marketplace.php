<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;

use BLZ_AFFILIATION\Utils\FileGetContents;
use BLZ_AFFILIATION\AffiliateMarketing\Offer;
use BLZ_AFFILIATION\AffiliateMarketing\Request;

abstract class Marketplace {

    /// i componenti del template di base della query
    protected $apiBase    = 'https://querydispatcher.justearn.it/api/v1/getoffer/';
    protected $apiQuery   = '{{ query }}/marketplace/{{ marketplace }}';
    protected $apiParams  = '/items/1/category/elettronica?min_price={{ min_price }}';

    protected $name = '';
    
    /// la richiesta 
    protected $request;

    public function __construct( Request $request ) {

        $this->request = $request;                
    }

    /**
     * Ritorna le offerte 
     *
     * @return array
     */
    public function getOffers() {

        $offers_json = FileGetContents::getContent( $this->getQueryURL() );

        return array_map( function( $offer ) {

            return new Offer( $offer );

        }, json_decode( $offers_json, true ) );

    }

    /**
     * Serve ad ogni Marketplace per definire il modo
     * in cui scrivere la richiesta all'api 
     * querydispatcher
     *
     * @return string
     */
    abstract protected function getQueryURL();

}
