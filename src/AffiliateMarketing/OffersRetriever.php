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
    private $req_transient_id;
    const TRANSIENT_DURATION = 10800; // 3 ore
    

    public function __construct( Request $request, Array $marketplaces = null ) {

        $this->marketplaces = ($marketplaces) ? $marketplaces : [$request->getMarketplace()];
        $this->request = $request;

        /// l'id del transient deve essere di massimo 172
        $this->req_transient_id = substr( $this->request->toString() , 0, 172);
    }

    /**
     * Cerca le offerte nella cache interna di wp e se non le 
     * trova effettua le chiamate a tutti i marketplace 
     *       
     * @return array - ritorna le offerte raccolte
     */
    public function getOffers() {

        /// getCachedOffers
        $offers = get_transient( $this->req_transient_id );

        //var_dump( $offers ); 

        /// if the transient does not have a value, or has expired, then get_transient will return false
        if( $offers === false ) {

            $offers = $this->getCalculatedOffers();

            /// setCachedOffers
            set_transient( $this->req_transient_id, $offers, self::TRANSIENT_DURATION );            
        }

        return $offers;
    }

    /**
     * Effettua le chiamate a tutti i marketplace e ritorna le offerte raccolte
     *
     * @return array - ritorna le offerte raccolte     
     */
    protected function getCalculatedOffers() {

        return array_reduce( $this->marketplaces, function( $result, $marketplace ) {

            //FIX FOR RETROCOMPATIBILIY
            if ($marketplace == "IT" ) $marketplace = "Amazon";

            $base = 'BLZ_AFFILIATION\\AffiliateMarketing\\Marketplaces\\';
            $className = $base. $marketplace;
            $class = new $className($this->request);
            $result = array_merge( $result, $class->getOffers() );
       
            return $result;

        }, []);

    }
}

