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
                return '<span class="order-higher-indicator up" data-id="'.$this->name.'" aria-hidden="true"></span>';
                break;
            case "DOWN":
                return '<span class="order-lower-indicator down" data-id="'.$this->name.'" aria-hidden="true"></span>';
                break;
        }
        ?>
        <?php
    }
}