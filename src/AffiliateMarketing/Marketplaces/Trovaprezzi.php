<?php

namespace BLZ_AFFILIATION\AffiliateMarketing\Marketplaces;
use BLZ_AFFILIATION\Utils\Settings;
class Trovaprezzi extends Marketplace {
    

    public function getQueryURL() {

        $query = $this->request->hasCode() ? $this->request->getCode() : $this->request->getKeyword();
        $code_suffix = $this->request->hasCode() ? '&code=true' : "";
        /// sostituisce i valori nella query
        $apiQuery = str_replace(['{{ query }}', '{{ marketplace }}'], [ urldecode( $query ), Settings::getApiSlug("trovaprezzi")], $this->apiQuery );
        $apiParams = str_replace('{{ min_price }}', $this->request->getMinPrice() , $this->apiParams );

        return $this->apiBase . $apiQuery . $apiParams . $code_suffix;
    }
}

