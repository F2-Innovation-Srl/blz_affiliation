<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;

/**
 * E' un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
abstract class Field {

    public $render;
    protected $name;
    protected $value;
    protected $type;
    protected $params;
    
    /**
     * prende in ingresso un nome e un valore per il field
     *
     * @param string $name
     * @param string $value
     * @param string $type
     * @param array $params
     */
    public function __construct( string $name, string $value = '', string $type = '', array $params = []) {

        $this->name   = $name;
        $this->value  = $value;
        $this->type   = $type;
        $this->params = $params;
    }

    public function Render() {
        return $this->Create();
    }

    /**
     * Returns the HTML markup of the field
     *
     * @return string
     */
    public abstract function Create();
}
