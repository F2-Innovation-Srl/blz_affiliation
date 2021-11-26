<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Text extends Field {

    /// viene richiamata dal costruttore
    public function Create() {
        switch ($type) {
            case "number":
                ?>
                <input type="number" style="width:70px" name="<?php echo $this->name?>" value="<?php echo $this->value?>" />
                <?php
                break;
            case "string":
                ?>
                <input type="text" name="<?php echo $this->name?>" value="<?php echo $this->value?>" />
                <?php
                break;
            case "boolean":
                ?>
                SI <input type="radio" name="<?php echo $this->name?>" <?php echo ($this->value == "true") ? "checked" : ""?> value="true" />
                NO <input type="radio" name="<?php echo $this->name?>" <?php echo ($this->value == "false") ? "checked" : ""?> value="false" />
                <?php
                break;
            case "add":
                ?>
                <input type="submit" name="submit" class="button button-primary" value="Aggiungi">
                <?php
                 break;
            case "update":
                ?>
                <input type="hidden" name="<?php echo $this->name?>" value="<?php echo $this->value?>" />
                <input type="submit" name="submit" class="button button-primary" value="Update">
                <?php
                break;
            case "delete":
                ?>
                <input type="hidden" name="<?php echo $this->name?>" value="<?php echo $this->value?>" />
                <input type="submit" name="submit" class="button button-primary" value="cancella">
                <?php
                break;
        }
        ?>
        <?php
    }
}