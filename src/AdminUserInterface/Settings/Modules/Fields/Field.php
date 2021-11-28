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
    protected $params;
    
    /// prende in ingresso un nome e un valore per il field
    public function __construct( $name, $value = '', $type = '', $params = []) {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->params = $params;
    }

    public function Render() {
        return $this->Create();
    }

    public abstract function Create();
}
