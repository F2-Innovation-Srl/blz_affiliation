<?php

namespace BLZ_AFFILIATION\Rendering\ParseLinkAndRender\Patterns;

/**
 * Un pattern è il testo del link che deve essere
 * sostituito, specifico per ogni marketplace
 * da esso estraiamo la url del link da costruire
 * oltre che il marketplace
 */
class EbayShorted extends Ebay {

    /// il pattern da riconoscere nel testo da ridefinire
    protected $pattern = '/<a.*?href="(https?:\/\/.*?ebay\.us.*?)".*?>/';
    
    protected $tracking_code = '';    
}