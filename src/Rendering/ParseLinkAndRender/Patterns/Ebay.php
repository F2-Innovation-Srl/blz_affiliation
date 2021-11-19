<?php

namespace BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Patterns;

use BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Link;

/**
 * Un pattern è il testo del link che deve essere
 * sostituito, specifico per ogni marketplace
 * da esso estraiamo la url del link da costruire
 * oltre che il marketplace
 */
class Ebay extends Pattern {

    /// il pattern da riconoscere nel testo da ridefinire
    protected $pattern = '/<a[^>]*href="(https:\/\/www.ebay.it[^"]*?)".*?>/';

    protected $tracking_code = 'ebay';
    
    /// il nome del marketplace di cui parsare i link
    protected $name = 'ebay';
    
    /// viene richiamata dal costruttore
    public function Parse() {

        preg_match_all( $this->pattern, $this->content, $matches );

        return array_map( function( $link , $url ) {

            return new Link ([
                'old_link'    => $link,
                'url'         => $url . '?mkcid=1&mkrid=724-53478-19255-0&siteid=101&campid=5338741871&customid={tracking_id}',
                'marketplace' => $this->name
            ]);

        }, $matches[ 0 ], $matches[ 1 ] );
    }
}
