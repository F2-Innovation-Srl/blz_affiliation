<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;
class Trovaprezzi extends Marketplace {

    protected $name = "trovaprezziVG";

    public function getQueryURL() {

        $query = $this->request->hasCode() ? $this->request->getCode() : $this->request->getKeyword();

        /// sostituisce i valori nella query
        $apiQuery = str_replace(['{{ query }}', '{{ marketplace }}', '{{ min_price }}'], [ urldecode( $query ), $this->name, $this->min_price ], $this->apiQuery );
        
        return $this->apiBase . $apiQuery . $this->apiParams;
    }
}

