<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class ConfigSettingsTable
 *
 * @package BLZ_AFFILIATION
 */
class ConfigSettingsTable extends Table{
    

	protected function getTableFields($row) {

        
        $config = ( $row != "" ) ? $row : "";
   
        $this->rows[] =  [
            "Configuration" => new Fields\Text( $this->option_name."_config", stripslashes($config), "textarea" ),
           
        ];
    }

    /**
     * Ritorna una riga
     *
     * @param [type] $this->option_name
     * @return array
     */
    protected function getAndSetRows(){
        
        // GET
        $row = stripslashes(get_option("blz-affiliation"));

        // UPDATE
        $row = isset( $_POST[$this->option_name. '_config'] ) ? $_POST[$this->option_name. '_config' ] : ($row ?? '');

        // SET
        update_option("blz-affiliation", $row );

        //RETURN
        return $row;
    }
}