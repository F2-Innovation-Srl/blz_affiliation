<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Activator extends Field {

    private $listActivator = [ "POSTTYPE", "USERS" ];

    /// viene richiamata dal costruttore
    public function Create() {
        
        $global_config = get_option("blz-affiliation-settings");

        if ( isset( $global_config[ "taxonomy" ] ) ) {

            $this->listActivator = array_merge( $this->listActivator, $global_config[ "taxonomy" ] );
        }
                           
        /// template della select
        $output =  <<<HTML
            <select class="attivatore" name="{{ name }}"><option value="">Seleziona un attivatore</option>{{ options }}</select>';
        HTML;

        /// crea le options
        $options = array_reduce( $this->listActivator, function( $markup, $activator ) { 

            $selected = ($this->value == $activator) ? "selected" : "";

            $markup .= '<option value="'.$activator.'" '.$selected.' >'.$activator.'</option>';
            return $markup;

        }, '<option value="tutte" '. ( ($this->value == "tutte") ? "selected" : "" ) .' >Tutti gli attivatori</option>' );


        return str_replace( [ '{{ name }}', '{{ options }}' ], [ $this->name, $options ],  $output );        
    }
    
}

