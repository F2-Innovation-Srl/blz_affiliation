<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields\Text;
 
/** 
 * Campi per impostare gli stili
 *
 */
class DisclamerTable extends Table{


	protected function getTableFields($row) {

        $this->title = "Disclamer "; 
    
        $disclamer = ( $row[ 'disclamer' ] != null ) ? $row[ 'disclamer' ] : '';
        /// compone una riga ( insieme di campi )
        $this->rows[] =  [
            /// inserisce un campo "casella di testo"
            'Disclamer (per skinnare impostare le proprietà alla classe css "blz_affiliation_disclamer")' => new Text( $this->option_name."_disclamer", $disclamer, "text" ),
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
            'disclamer' => isset( $_POST[$this->option_name. '_disclamer'  ] ) ? $_POST[$this->option_name. '_disclamer' ]  : ( $row[ 'disclamer'  ] ?? '' ),            
        ];

        // SET
        update_option( $this->option_name, $row );

        //RETURN
        return $row;
    }
}