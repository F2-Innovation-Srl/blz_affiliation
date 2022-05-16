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
    protected $pattern = '/<a[^>]*href="(https?:\/\/www.ebay.it[^"]*?)".*?>/';

    protected $tracking_code = 'ebay';
    
    /// il nome del marketplace di cui parsare i link
    public $name = 'ebay';
    
    /// viene richiamata dal costruttore
    public function Parse() {

        preg_match_all( $this->pattern, $this->content, $matches );

        return array_map( function( $link , $url ) {
            
            $url = ( strpos( $url, 'tag=' ) === false ) ? $url : preg_filter('/(.*)\?.*/', '$1', $url );

            $params = implode( '&', [
                'mkevt=1',
                'toolid=10001',
                'mkcid=1',
                'mkrid=724-53478-19255-0',
                'siteid=101',
                'campid=5338741871',
                'customid={tracking_id}'
            ]);

            $prefix = strpos( $url, '?' ) === false ? '?' : '&';

            $url = $url . $prefix . $params;

            return new Link ([
                'old_link'    => $link,
                'url'         => $url,
                'marketplace' => $this->name
            ]);

        }, $matches[ 0 ], $matches[ 1 ] );
    }
}
