<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Arrow extends Field {

    /// viene richiamata dal costruttore
    public function Create() {
        
        switch ($this->type) {
            case "UP":
                return '<input type="submit" name="btnSubmit" data-id="'.$this->name.'" data-name="'.$this->params["hidden_field"].'" class="button button-primary up" value="&uarr;">';
                break;
            case "DOWN":
                return '<input type="submit" name="btnSubmit" data-id="'.$this->name.'" data-name="'.$this->params["hidden_field"].'" class="button button-primary down" value="&darr;">';
                break;
        }
        ?>
        <?php
    }
}