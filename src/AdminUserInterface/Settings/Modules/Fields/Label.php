<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Label extends Field {

    private $settings = [
        'GA'     => [ 'tab' => 'ga_event_template' ],
        'TRK_ID' => [ 'tab' => 'tracking_id' ]
    ];
    
    protected $output =
    <<<HTML
        <select class="label" name="{{ name }}">
            <option value="">Seleziona una {{ type }} label</option>
            {{ options }}
        </select>
    HTML;

    protected $option = '<option value="{{ value }}"{{ selected }}>{{ label }}</option>';   


    /// viene richiamata dal costruttore
    public function Create() {
        
        $typeGA    = ( $this->type == "GA"     && !empty( $this->params["marketplace"]["ga_event_template"] ));
        $typeTRKID = ( $this->type == "TRK_ID" && !empty( $this->params["marketplace"]["tracking_id"] ));

        if( !$typeGA && !$typeTRKID ) {

            return '<input type="hidden" id="'.$this->name.'" name="'.$this->name.'" value="'.$this->value.'" />'; 
        }
        
        $labels = $this->getLabels( $this->params[ "marketplace" ][ $this->settings[ $this->type][ 'tab' ] ], "{", "}" );
        
        $options = array_map( function( $label ) {

            $selected = ( $this->value == $label ) ? ' selected ' : '';

            return str_replace(
                [ '{{ value }}'.'{{ label }}','{{ selected }}' ],
                [ $label, $label, $selected ],
                $this->option
            );
            
        }, $labels);
        
        return str_replace(
            [ '{{ name }}', '{{ type }}', '{{ options }}' ],
            [ $this->name, $this->type, $options ],
            $this->output
        );
    }

    
    private function getLabels(string $str, string $startDelimiter, string $endDelimiter) {
    
        $regex = '/' .$startDelimiter . '(.*?)'. $endDelimiter .'/';
        
        /// prende tutti i termini racchiusi nei delimitatori
        preg_match_all( $regex, $str, $matches);

        /// rimuove gli spazi intorno ai terms
        $terms = isset( $matches[1]) ? array_map( function( $term ) {  return trim( $term );  }, $matches[1] ) : [];

        /// rimuove 'website' se esiste;
        $terms = array_values( array_filter( $terms, function( $term ) { return $term != 'website'; }) );
        
        return $terms;
    }

}