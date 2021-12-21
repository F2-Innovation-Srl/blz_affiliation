<?php

namespace BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Patterns;


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
    protected $name = '';
    
    /// prende in ingresso un pattern
    public function __construct( string $content ) {

        $this->content = $content;
        
        $this->data = $this->Parse();
    }
    public function getName() { return self::$name; }
    public abstract function Parse();
}
