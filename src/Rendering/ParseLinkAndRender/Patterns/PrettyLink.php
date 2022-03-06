<?php

namespace BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Patterns;

use BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Link;

/**
 * Un pattern è il testo del link che deve essere
 * sostituito, specifico per ogni marketplace
 * da esso estraiamo la url del link da costruire
 * oltre che il marketplace
 */
class PrettyLink extends Pattern {

    /// il pattern da riconoscere nel testo da ridefinire
    protected $pattern = '/<a.*?href="(https?:\/\/.*?mtz-editorial.*?)".*?>/';

    protected $tracking_code = '';
    
    /// il nome del marketplace di cui parsare i link
    public $name = '';
       
    /// viene richiamata dal costruttore
    public function Parse() {

        preg_match_all( $this->pattern, $this->content, $matches );

        return array_map( function( $link, $url ) {

            /// per indicare il  marketplace si usa il prefisso '--'
            $marketplace = preg_filter( '/.*?--(.*)/', '$1', $url );

            /// se ha con se il tracking id
            //  lo separiamo dalla url
            $url = ( $marketplace ) ? preg_filter( '/(.*?)--.*/', '$1', $url ) : $url;

            return new Link ([
                'old_link'    => $link,
                'url'         => $url,
                'marketplace' => ( $marketplace ) ? $marketplace : ''
            ]);

        }, $matches[0], $matches[1] );
    }
}