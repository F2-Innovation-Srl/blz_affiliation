<?php

namespace BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Patterns;

use BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Link;

/**
 * Un pattern è il testo del link che deve essere
 * sostituito, specifico per ogni marketplace
 * da esso estraiamo la url del link da costruire
 * oltre che il marketplace
 */
class AmazonPrimeVideo extends Pattern {

    /// il pattern da riconoscere nel testo da ridefinire
    protected $pattern = '/<a[^>]*href="(https?:\/\/www.primevideo.com[^"]*?)".*?>/';

    protected $tracking_code = 'amazon';
    
    /// il nome del marketplace di cui parsare i link
    public $name = 'amazon';
       
    /// viene richiamata dal costruttore
    public function Parse() {

        preg_match_all( $this->pattern, $this->content, $matches );

        return array_map( function( $link, $url ) {

            $url = ( strpos( $url, 'tag=' ) === false ) ? $url : preg_filter('/(.*)\?.*/', '$1', $url );
            $url = ( strpos( $url, '?' ) === false ) ? $url .'?tag={tracking_id}' :  $url .'&tag={tracking_id}';

            return new Link ([
                'old_link'    => $link,
                'url'         => $url,
                'marketplace' => $this->name
            ]);

        }, $matches[0], $matches[1] );
    }
}
