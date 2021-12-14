<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Activator extends Field {

    private $listActivator = ["POSTTYPE","USERS"];

    /// viene richiamata dal costruttore
    public function Create() {
        
        $global_config = get_option("blz-affiliation-settings");
        if (isset($global_config["taxonomy"]))
            $listActivator = array_merge($this->listActivator,$global_config["taxonomy"]);

        $output = '<select class="attivatore" name="'.$this->name.'"><option value="">Seleziona un attivatore</option>';
        $output.= '<option value="tutte" '.(($this->value == "tutte") ? "selected" : "").' >Tutti gli attivatori</option>';
        foreach( $listActivator as $activator) 
            $output.= '<option value="'.$activator.'" '.(($this->value == $activator) ? "selected" : "").' >'.$activator.'</option>';
        $output.= '</select>';
        return $output;
    }
}