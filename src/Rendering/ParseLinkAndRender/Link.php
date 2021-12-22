<?php

namespace BLZ_AFFILIATION\Rendering\ParseLinkAndRender;

/**
 * definisce la struttura di un link 
 */
class Link {

    public $old_link;
    public $url;
    public $marketplace;

    public function __construct( array $args ) 
    {
        $this->old_link    = $args['old_link'];
        $this->url         = $args['url'];
        $this->marketplace = $args['marketplace'];        
    }
}
