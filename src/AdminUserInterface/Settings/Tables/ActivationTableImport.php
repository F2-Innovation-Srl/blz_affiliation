<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ActivationTableImport extends Table {

	protected function getTableFields($rows) {

        $this->title = "Importa da altra tabella di attivazione";

        $this->rows[] =  [
            "Attivatori" => (new Fields\Import($this->option_name."_activation_import","","",["current" => $this->option_name])),
            "Azioni" => (new Fields\Text($this->option_name."_new",'Importa',"button"))
        ];
    }

    protected function getAndSetRows(){
        
        if (isset( $_POST[$this->option_name])  && !empty($_POST[$this->option_name]) ){
            $activationRows = get_option( $_POST[$this->option_name."_activation_import"]);    
            update_option($this->option_name,$activationRows);
        }
    }
}