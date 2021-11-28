<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Taxonomy extends Field {

    /// viene richiamata dal costruttore
    public function Create() {
       
        $output = "<select name=\"".$this->name."\"><option value=\"0\">Seleziona un postype</option>";
            $taxonomies = get_taxonomies();
            print_r($taxonomies);exit;
            foreach( $taxonomies as $taxonomy) 
                $output .= "<option value=\"".$taxonomy."\" ".(($this->value == $taxonomy) ? "selected" : "")." >".$taxonomy."</option>";
                
        $output .= "</select>";
        return $output;
    }

}