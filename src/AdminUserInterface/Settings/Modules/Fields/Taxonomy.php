<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Taxonomy extends Field {

    /// viene richiamata dal costruttore
    public function Create() {
       
        $output = "<select size=\"8\" multiple name=\"".$this->name."[]\">";
            $taxonomies = get_taxonomies();
            foreach( $taxonomies as $taxonomy) 
                $output .= "<option value=\"".$taxonomy."\" ".((in_array($taxonomy,$this->value)) ? "selected" : "")." >".$taxonomy."</option>";
                
        $output .= "</select>";
        return $output;
    }

}