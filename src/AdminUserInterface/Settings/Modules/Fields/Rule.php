<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Rule extends Field {

    /// viene richiamata dal costruttore
    public function Create() {
        ?>
        <input type="number" style="width:70px" name="<?php echo $this->name?>" value="<?php echo $this->value?>" />     
        <?php
    }

    private function printUsers(){
        ?>
            <select name="<?php echo $name?>"><option value="0">Seleziona un utente</option>
            <?php
            $blogusers = get_users(['role__in' => ['author', 'subscriber']]);
            foreach( $blogusers as $user) :?>
                <option value="<?php echo $user->display_name?>" <?php echo ($value == $user->display_name) ? "selected" : ""?> ><?php echo $user->display_name?></option>
            <?php endforeach;?>
            </select>
        <?php
    }
}