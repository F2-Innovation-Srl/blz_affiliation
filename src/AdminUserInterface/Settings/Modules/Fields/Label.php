<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Label extends Field {

    /// viene richiamata dal costruttore
    public function Create() {
        $labels = [];
        $output = '<select class="label" name="'.$this->name.'"><option value="">Seleziona una '.$this->type.' label</option>';
        switch ($this->type) {
            case "GA":
                $labels = $this->getLabels($this->params["tab"]["ga_event_template"],"{","}");
                break;
            case "TRACK":
                $labels = $this->getLabels($this->params["tab"]["tracking_id"],"{","}");
                break;
        } 
        foreach($labels as $label)
            $output.= '<option value="'.$activator.'" '.(($this->value == $label) ? "selected" : "").' >'.$label.'</option>';    
        $output.= '</select>';
        return $output;
    }

    private function getLabels($str, $startDelimiter, $endDelimiter) {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
          $contentStart += $startDelimiterLength;
          $contentEnd = strpos($str, $endDelimiter, $contentStart);
          if (false === $contentEnd) {
            break;
          }
          $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
          $startFrom = $contentEnd + $endDelimiterLength;
        }
      
        return $contents;
    }
}