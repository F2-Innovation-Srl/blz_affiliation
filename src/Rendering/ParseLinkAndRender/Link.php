<?php

namespace BLZ_AFFILIATION\Rendering\ParseLinkAndRender;

/**
 * definisce la struttura di un link 
 */
class Link {

    public $old_string;
    public $url;
    public $marketplace;

    public function __construct( array $args ) 
    {
        $this->old_string  = $args['old_string'];
        $this->url         = $args['url'];
        $this->marketplace = $args['marketplace'];        
    }
}
