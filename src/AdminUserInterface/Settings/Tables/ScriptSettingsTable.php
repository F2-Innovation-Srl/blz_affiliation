<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields\Text;
 
/** 
 * Campi per impostare gli stili
 *
 */
class ScriptSettingsTable extends Table{


	protected function getTableFields($row) {

        $this->title = "Functions Settings"; 
    
        $tracker_enable            = ( $row[ 'tracker_enable' ] != null ) ? $row[ 'tracker_enable' ] : 'true';
        $tracking_enable            = ( $row[ 'tracking_enable' ] != null ) ? $row[ 'tracking_enable' ] : 'true';
        /// compone una riga ( insieme di campi )
        $this->rows[] =  [
            "Abilita filtro tracking id (tag=) " => new Fields\Text( $this->option_name."_tracking_enable", $tracking_enable, "boolean" ),
            "Abilita tracker.js" => new Fields\Text( $this->option_name."_tracker_enable", $tracker_enable, "boolean" )
        ];
    }


    /**
     * Ritorna una riga
     *
     * @return array
     */
    protected function getAndSetRows( ){
        
        // GET
        $row = get_option( $this->option_name);

        // UPDATE
        $row = [
            'tracking_enable'   => isset( $_POST[ $this->option_name.'_tracking_enable' ] ) ? $_POST[$this->option_name. '_tracking_enable' ] : ($row['tracking_enable'] ?? 'true'),
            'tracker_enable'   => isset( $_POST[ $this->option_name.'_tracker_enable' ] ) ? $_POST[$this->option_name. '_tracker_enable' ] : ($row['tracker_enable'] ?? 'true')
        ];

        // SET
        update_option( $this->option_name, $row );

        //RETURN
        return $row;
    }
}