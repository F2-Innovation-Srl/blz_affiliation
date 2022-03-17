<?php

namespace BLZ_AFFILIATION\AffiliateMarketing;

use BLZ_AFFILIATION\AffiliateMarketing\Request;
/**
 * Prende in ingresso alcuni parametri di una richiesta (Request) 
 * e un elenco di nomi di marketplace
 * Ritorna un array di Offer che sono generate dalle chiamate ai relativi marketplace
 */
class OffersRetriever {

    private $marketplaces;
    private $request;

    public function __construct( Request $request, Array $marketplaces = null ) {

        $this->marketplaces = ($marketplaces) ? $marketplaces : [$request->getMarketplace()];
        $this->request = $request;
    }

    /**
     * Effettua le chiamate a tutti i marketplace e ritorna le offerte raccolte
     */
    public function getOffers() {

        return array_reduce( $this->marketplaces, function( $result, $marketplace ) {

            $base = 'BLZ_AFFILIATION\\AffiliateMarketing\\Marketplaces\\';
            $className = $base. $marketplace;
            $class = new $className($this->request);
            $result = array_merge( $result, $class->getOffers() );
       
            return $result;

        }, []);

    }
}

