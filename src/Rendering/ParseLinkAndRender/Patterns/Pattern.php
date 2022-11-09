<?php

namespace BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Patterns;

use BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Link;


/**
 * Un pattern è il testo del link che deve essere
 * sostituito, specifico per ogni marketplace
 * da esso estraiamo la url del link da costruire
 * oltre che il marketplace
 */
abstract class Pattern {

    /// array di oggetti con il 
    /// - link da spostituire
    /// - marketplace
    /// - altri dati 
    public $data;
    
    /// il pattern da riconoscere nel testo da ridefinire
    protected $pattern = '';
    
    /// il nome del marketplace di cui parsare i link
    public $name = '';
    
    /// prende in ingresso un pattern
    public function __construct( string $content ) {

        $this->content = $content;
        
        $this->data = $this->Parse();
    }
    
    /**
     * Prova a cercare il pattern dei link di per il prendendo 
     * il pattern che viene impostato nella property 'pattern'
     * 
     * ( normalmnte  viene richiamata dal costruttore )
     *
     * @return array - array vuoto se non trova nessun link corrispondente 
     *                 al markerplace, altrimenti [ old_link, url, marketplace ];
     * 
     */
    public function Parse() {

        preg_match_all( $this->pattern, $this->content, $matches );

        return array_map( function( $link, $url ) {
            
            return new Link ([
                'old_link'    => $link,
                'url'         => $url,
                'marketplace' => $this->name
            ]);

        }, $matches[0], $matches[1] );
    }

}
