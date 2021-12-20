<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Config;
/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Import extends Field {

      /// viene richiamata dal costruttore
    public function Create() {
        $config = Config::loadSettings();
        $output = '<select class="attivatore_import" name="'.$this->name.'"><option value="">Seleziona da dove importare</option>';
        
        foreach($config->pages[0]->controller->settings["tabs"] as $item)
            foreach($item["marketplaces"] as $marketplace) {
                $option_value = $config->pages[0]["slug"]."-".$item["slug"]."-".$marketplace["slug"];
                $option_name = $item["name"]." - ".$marketplace["name"];
                if ($this->params["current"] != $option_value)
                    $output.= '<option value="'.$option_value.'" >'.$option_name.'</option>';   
            }
        $output.= '</select>';
        return $output;
    }
}