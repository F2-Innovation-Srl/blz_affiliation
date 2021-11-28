<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Activator extends Field {

    private $listActivator = ["POSTTYPE","TAXONOMY","TAG","USERS"];

    /// viene richiamata dal costruttore
    public function Create() {
        $output = '<select onchange="this.GaTrakingIdSettings.submit()" class="attivatore" name="'.$this->name.'"><option value="">Seleziona un attivatore</option>';
        foreach( $this->listActivator as $activator) 
            $output.= '<option value="'.$activator.'" '.(($this->value == $activator) ? "selected" : "").' >'.$activator.'</option>';
        $output.= '</select>';
        return $output;
    }
}