<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Activator extends Field {

    private $listActivator = ["POSTTYPE","TAG","USERS"];

    /// viene richiamata dal costruttore
    public function Create() {
        
        $global_config = get_option("blz-affiliation-settings");
        if (isset($global_config[0]["taxonomy"]))
            $listActivator = array_merge($global_config[0]["taxonomy"],$listActivator);

        $output = '<select class="attivatore" name="'.$this->name.'"><option value="">Seleziona un attivatore</option>';
        foreach( $this->listActivator as $activator) 
            $output.= '<option value="'.$activator.'" '.(($this->value == $activator) ? "selected" : "").' >'.$activator.'</option>';
        $output.= '</select>';
        return $output;
    }
}