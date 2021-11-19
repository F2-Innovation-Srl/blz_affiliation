<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;
class Trovaprezzi extends Marketplace {

    protected $name = "trovaprezziVG";

    public function getQueryURL() {

        $query = $this->request->hasCode() ? $this->request->getCode() : $this->request->getKeyword();

        $apiQuery = str_replace(['{{ query }}', '{{ marketplace }}'], [ urldecode( $query ), $this->name ], $this->apiQuery );
        
        return $this->apiBase . $apiQuery . $this->apiParams;
    }
}

