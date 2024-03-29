<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;

use BLZ_AFFILIATION\Utils\HttpRequest;
use BLZ_AFFILIATION\AffiliateMarketing\Offer;
use BLZ_AFFILIATION\AffiliateMarketing\Request;
abstract class Marketplace {

    /// i componenti del template di base della query
    protected $apiBase    = 'https://querydispatcher.justearn.it/api/v1/getoffer/';
    protected $apiQuery   = '{{ query }}/marketplace/{{ marketplace }}';
    protected $apiParams  = '/items/1/category/elettronica?min_price={{ min_price }}';

    protected $name = '';
    protected $panelName = "";
    protected $panelDescription = '';
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

        $offers_json = HttpRequest::getContent( $this->getQueryURL() );

        /// se non ci sono offerte ritorna l'insieme vuoto
        if ( empty( $offers_json ) ) return [];

        $offers = json_decode( $offers_json, true );

        /// se non è corretto il risultato ritorna l'insieme vuoto
        /// altrimenti le offerte secondo template Offer
        return ( !is_array( $offers ) ) ? [] :  array_map( function( $offer ) {

            return new Offer( $offer );

        }, $offers );        
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
