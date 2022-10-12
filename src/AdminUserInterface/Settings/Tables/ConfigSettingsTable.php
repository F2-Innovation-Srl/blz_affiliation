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

        
        $config             = ( $row[ 'config' ]     != null ) ? $row[ 'config' ]     : '';
       

        $this->rows[] =  [
            "Configuration" => new Fields\Textarea( $this->option_name."_config", $ga_code, "text" ),
           
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
        $row = get_option( $this->option_name );

        // UPDATE
        $row = [
            'config'            => isset( $_POST[$this->option_name. '_config'  ] ) ? $_POST[$this->option_name. '_config' ]  : ($row[ 'config'  ] ?? ''),
        ];

        // SET
        update_option( $this->option_name, $row );

        //RETURN
        return $row;
    }
}