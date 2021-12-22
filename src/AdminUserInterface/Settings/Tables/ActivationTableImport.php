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
            "Attivatori" => (new Fields\Import($option_name."_activation_import","","",["current" => $option_name])),
            "Azioni" => (new Fields\Text($option_name."_new",'Importa',"button"))
        ];
    }

    protected function getAndSetRows($option_name){
        
        if (isset( $_POST[$option_name])  && !empty($_POST[$option_name]) ){
            $activationRows = get_option( $_POST[$option_name."_activation_import"]);    
            update_option($option_name,$activationRows);
        }
    }
}