<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * E' un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
abstract class Field {

    public $render;
    protected $name;
    protected $value;
    protected $type;
    protected $properties;
    
    /// prende in ingresso un nome e un valore per il field
    public function __construct( string $name, String $value = '', String $type = '', String $properties = '' ) {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->properties = $properties;
        $this->render = $this->Create();
    }

    public function Render() {
        return $this->render;
    }

    public abstract function Create();
}
