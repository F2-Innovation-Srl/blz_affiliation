<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Import extends Field {

      /// viene richiamata dal costruttore
    public function Create() {
        
        $output = '<select class="attivatore_import" name="'.$this->name.'"><option value="">Seleziona da dove importare</option>';
        $this->items = CONFIG["Items"];
        foreach(CONFIG["Items"][0]["settings"]["tabs"] as $item)
            foreach($item["marketplaces"] as $marketplace) {
                $option_value = CONFIG["Items"][0]["suffix"]."-".$item["suffix"]."-".$marketplace["suffix"];
                $option_name = $item["name"]." - ".$marketplace["name"];
                if ($this->params["current"] != $option_value)
                    $output.= '<option value="'.$option_value.'" >'.$option_name.'</option>';   
            }
        $output.= '</select>';
        return $output;
    }
}