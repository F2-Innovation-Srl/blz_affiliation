<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Label extends Field {

    /// viene richiamata dal costruttore
    public function Create() {
        $labels = [];
        if (($this->type == "GA" && !empty($this->params["marketplace"]["ga_event_template"])) OR ($this->type == "TRK_ID" && !empty($this->params["tab"]["tracking_id"]))){
            $output = '<select class="label" name="'.$this->name.'"><option value="">Seleziona una '.$this->type.' label</option>';
            switch ($this->type) {
                case "GA":
                    $labels = $this->getLabels($this->params["marketplace"]["ga_event_template"],"{","}");
                    break;
                case "TRK_ID":
                    $labels = $this->getLabels($this->params["marketplace"]["tracking_id"],"{","}");
                    break;
            } 
            foreach($labels as $label)
                $output.= '<option value="'.$label.'" '.(($this->value == $label) ? "selected" : "").' >'.$label.'</option>';    
            $output.= '</select>';
        }else{
            $output= '<input type="hidden" id="'.$this->name.'" name="'.$this->name.'" value="'.$this->value.'" />'; 
        }
        return $output;
    }

    private function getLabels($str, $startDelimiter, $endDelimiter) {
        $contents = [];
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
          $contentStart += strlen($startDelimiter);
          $contentEnd = strpos($str, $endDelimiter, $contentStart);
          if (false === $contentEnd)  break;
          $content = substr($str, $contentStart, $contentEnd - $contentStart);
          if ("website" != $content) $contents[] = $content;
          $startFrom = $contentEnd + strlen($endDelimiter);
        }
        return $contents;
    }
}