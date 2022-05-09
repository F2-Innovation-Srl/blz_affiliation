<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Text extends Field {

    /// viene richiamata dal costruttore
    public function Create() {
        $hidden_field = isset($this->params["hidden_field"]) ? $this->params["hidden_field"] : "";
        switch ($this->type) {
            case "number":
                return '<input type="number" name="'.$this->name.'" value="'.$this->value.'" />';
                break;
            case "text":
                return '<input type="text" style="width:100%" name="'.$this->name.'" value="'.$this->value.'" />';
                break;
            case "textarea":
                return '<textarea rows="15" cols="110" name="'.$this->name.'" >'.$this->value.'</textarea>';
                break;
            case "boolean":
                foreach (["NO" => "false","SI" => "true"] as $key => $options)
                    $output .= $key.' <input type="radio" name="'.$this->name.'" '.( ($this->value == $options) ? "checked" : "").' value="'.$options.'" />';
                return $output;
                break;
            case "hidden":
                return '<input type="hidden" id="'.$this->name.'" name="'.$this->name.'" value="'.$this->value.'" />';
                break;
            case "button":
                return '<input type="submit" name="btnSubmit" data-id="'.$this->name.'" data-name="'.$hidden_field.'" class="button button-primary '.strtolower($this->value).'" value="'.$this->value.'" />';
                break;
        }
        ?>
        <?php
    }
}