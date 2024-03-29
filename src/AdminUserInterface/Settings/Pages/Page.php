<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Pages;

class Page {

    public $name;
    public $slug;
    public $controller;

    public function __construct( array $args ) 
    {
        $this->name       = $args['name'];
        $this->slug       = $args['slug'];
        $this->controller = $args['controller'];        
    }
}
