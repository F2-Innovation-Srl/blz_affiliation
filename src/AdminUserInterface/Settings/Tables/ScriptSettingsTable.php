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
    
        $tracker_disable            = ( $row[ 'tracker_disable' ] != null ) ? $row[ 'tracker_disable' ] : 'false';
        $tracking_disable            = ( $row[ 'tracking_disable' ] != null ) ? $row[ 'tracking_disable' ] : 'false';
        /// compone una riga ( insieme di campi )
        $this->rows[] =  [
            "Disabilita filtro tracking id (tag=) " => new Fields\Text( $this->option_name."_tracking_disable", $tracking_disable, "boolean" ),
            "Disabilita tracker.js" => new Fields\Text( $this->option_name."_tracker_disable", $tracker_disable, "boolean" )
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
            'tracking_disable'   => isset( $_POST[ $this->option_name.'_tracking_disable' ] ) ? $_POST[$this->option_name. '_tracking_disable' ] : ($row['tracking_disable'] ?? 'false'),
            'tracker_disable'   => isset( $_POST[ $this->option_name.'_tracker_disable' ] ) ? $_POST[$this->option_name. '_tracker_disable' ] : ($row['tracker_disable'] ?? 'false')
        ];

        // SET
        update_option( $this->option_name, $row );

        //RETURN
        return $row;
    }
}