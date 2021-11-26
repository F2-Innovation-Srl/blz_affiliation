<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Activator extends Field {

    private $listActivator = ["POSTTYPE","CATEOGORY","TAXONOMY","TAG","USERS"];

    /// viene richiamata dal costruttore
    public function Create() {
        ?>
        <select name="<?php echo $this->name?>"><option value="">Seleziona un attivatore</option>
            <?php 
            
            foreach( $this->listActivator as $activator) :?>
                <option value="<?php echo $activator?>" <?php echo ($this->value == $activator) ? "selected" : ""?> ><?php echo $activator?></option>
            <?php endforeach;?>
        </select>
        <?php
    }
}