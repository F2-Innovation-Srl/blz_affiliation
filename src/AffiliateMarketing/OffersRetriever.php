<?php

namespace BLZ_AFFILIATION\AffiliateMarketing;

class OffersRetriever {

    private $marketplaces;

    public function __construct( array $marketplaces ) {

        $this->marketplaces = $marketplaces;
    }

    public function getOffers() {

        return array_reduce( $this->marketplaces, function( $result, $marketplace ) {

            $base = 'BLZ_AFFILIATION\\AffiliateMarketing\\Marketplaces\\';
            
            $result = array_merge( $result, ( new ($base.$marketplace)() )->getOffers() );

            return $result;

        }, []);

    }
}

