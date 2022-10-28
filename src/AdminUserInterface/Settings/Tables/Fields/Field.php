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
     * @param string $name   - nome del campo
     * @param string $value  - valore di default
     * @param string $type   - tipo di campo ( è un sottotipo previsto dalla specifica classe )
     * @param array  $params - parametri vari...
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
